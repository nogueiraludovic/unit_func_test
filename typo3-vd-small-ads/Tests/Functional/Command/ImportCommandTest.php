<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\Tests\Functional\Command;

use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use Vd\VdSmallAds\Command\ImportCommand;
use Vd\VdSmallAds\Domain\Repository\SmallAdsRepository;

use function getenv;
use function json_encode;
use function putenv;

use const JSON_THROW_ON_ERROR;

final class ImportCommandTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['vd/vd-small-ads'];

    private string|false $backupEnvUrl = false;

    #[Test]
    public function executeThrowsWhenWebserviceUrlIsNotConfigured(): void
    {
        putenv('TYPO3_VDSMALLADS_SERVICE_URL');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(1651242749);

        (new CommandTester($this->buildCommand()))->execute([
            'fileStorageUid' => '1',
            'storagePid' => '1',
            'uploadFolder' => 'test/',
        ]);
    }

    #[Test]
    public function executeThrowsWhenWebserviceUrlIsNotValid(): void
    {
        putenv('TYPO3_VDSMALLADS_SERVICE_URL=not-a-valid-url');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(1705654833);

        (new CommandTester($this->buildCommand()))->execute([
            'fileStorageUid' => '1',
            'storagePid' => '1',
            'uploadFolder' => 'test/',
        ]);
    }

    #[Test]
    public function importDataDoesNothingWhenJsonDecodesToNull(): void
    {
        $command = $this->getAccessibleMock(
            ImportCommand::class,
            ['flushCache', 'copyImagesToFileSystem'],
            $this->buildCommandArgs()
        );

        $command->expects(self::never())->method('flushCache');
        $command->expects(self::never())->method('copyImagesToFileSystem');

        $command->_call('importData', 'null');
    }

    #[Test]
    public function importDataPersistsSmallAdsRecordsFromJson(): void
    {
        $repository = $this->get(SmallAdsRepository::class);

        $command = $this->getAccessibleMock(
            ImportCommand::class,
            ['flushCache', 'copyImagesToFileSystem'],
            [
                $this->createMock(CacheManager::class),
                $this->get(ConnectionPool::class),
                $this->get(PersistenceManager::class),
                $repository,
                $this->createMock(StorageRepository::class),
            ]
        );
        $command->_set('storagePid', 1);
        $command->_set('uploadPath', '');

        $command->_call('importData', json_encode([
            [
                'uid' => 42,
                'cat' => 'sale',
                'cat2' => 'car',
                'comment' => 'Good condition',
                'content' => 'Nice car for sale',
                'crdate' => 1700000000,
                'displayemail' => false,
                'email' => 'seller@example.com',
                'image' => 0,
                'iscommercial' => false,
                'phone' => '0800000000',
                'reviewed' => true,
                'slug' => 'nice-car',
                'title' => 'Car for sale',
                'user' => 'john',
            ],
        ], JSON_THROW_ON_ERROR));

        $count = (int)$this->get(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_vdsmallads_domain_model_smallads')
            ->count('uid')
            ->from('tx_vdsmallads_domain_model_smallads')
            ->executeQuery()
            ->fetchOne();

        $this->assertSame(1, $count);

        $row = $this->get(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_vdsmallads_domain_model_smallads')
            ->select('title', 'cat', 'vdexternaluid')
            ->from('tx_vdsmallads_domain_model_smallads')
            ->executeQuery()
            ->fetchAssociative();

        $this->assertSame('Car for sale', $row['title']);
        $this->assertSame('sale', $row['cat']);
        $this->assertSame(42, (int)$row['vdexternaluid']);
    }

    #[Test]
    public function importDataSkipsImageWhenBase64IsEmpty(): void
    {
        $command = $this->getAccessibleMock(
            ImportCommand::class,
            ['flushCache', 'copyImagesToFileSystem'],
            $this->buildCommandArgs()
        );
        $command->_set('storagePid', 1);
        $command->_set('uploadPath', '');

        $command->expects(self::once())
            ->method('copyImagesToFileSystem')
            ->with([]);

        $command->_call('importData', json_encode([
            [
                'uid' => 10,
                'cat' => '',
                'cat2' => '',
                'comment' => '',
                'content' => '',
                'crdate' => 0,
                'displayemail' => false,
                'email' => '',
                'image' => 0,
                'iscommercial' => false,
                'phone' => '',
                'reviewed' => false,
                'slug' => '',
                'title' => 'No image',
                'user' => '',
            ],
        ], JSON_THROW_ON_ERROR));
    }

    #[Test]
    public function importDataCollectsImagesWhenBase64IsPresent(): void
    {
        $command = $this->getAccessibleMock(
            ImportCommand::class,
            ['flushCache', 'copyImagesToFileSystem'],
            $this->buildCommandArgs()
        );
        $command->_set('storagePid', 1);
        $command->_set('uploadPath', '');

        $command->expects(self::once())
            ->method('copyImagesToFileSystem')
            ->with(self::callback(static fn(array $images): bool => count($images) === 1 && $images[0]['vdexternaluid'] === 5));

        $command->_call('importData', json_encode([
            [
                'uid' => 5,
                'cat' => '',
                'cat2' => '',
                'comment' => '',
                'content' => '',
                'crdate' => 0,
                'displayemail' => false,
                'email' => '',
                'image' => 'data:image/png;base64,abc123',
                'iscommercial' => false,
                'phone' => '',
                'reviewed' => false,
                'slug' => '',
                'title' => 'With image',
                'user' => '',
            ],
        ], JSON_THROW_ON_ERROR));
    }

    #[Test]
    public function flushCacheReturnsEarlyWhenNoPluginsExist(): void
    {
        $cacheManager = $this->createMock(CacheManager::class);
        $cacheManager->expects(self::never())->method('flushCachesInGroupByTags');

        $command = $this->getAccessibleMock(
            ImportCommand::class,
            [],
            [
                $cacheManager,
                $this->get(ConnectionPool::class),
                $this->createMock(PersistenceManager::class),
                $this->createMock(SmallAdsRepository::class),
                $this->createMock(StorageRepository::class),
            ]
        );

        $command->_call('flushCache');
    }

    #[Test]
    public function flushCacheFlushesPageTagsForPluginPages(): void
    {
        $this->get(ConnectionPool::class)
            ->getConnectionForTable('tt_content')
            ->insert('tt_content', [
                'uid' => 1,
                'pid' => 99,
                'CType' => 'list',
                'list_type' => 'vdsmallads_pi1',
            ]);

        $cacheManager = $this->createMock(CacheManager::class);
        $cacheManager->expects(self::once())
            ->method('flushCachesInGroupByTags')
            ->with('pages', ['pageId_99']);

        $command = $this->getAccessibleMock(
            ImportCommand::class,
            [],
            [
                $cacheManager,
                $this->get(ConnectionPool::class),
                $this->createMock(PersistenceManager::class),
                $this->createMock(SmallAdsRepository::class),
                $this->createMock(StorageRepository::class),
            ]
        );

        $command->_call('flushCache');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->backupEnvUrl = getenv('TYPO3_VDSMALLADS_SERVICE_URL');
    }

    protected function tearDown(): void
    {
        if ($this->backupEnvUrl === false) {
            putenv('TYPO3_VDSMALLADS_SERVICE_URL');
        } else {
            putenv('TYPO3_VDSMALLADS_SERVICE_URL=' . $this->backupEnvUrl);
        }

        parent::tearDown();
    }

    private function buildCommand(): ImportCommand
    {
        return new ImportCommand(
            $this->createMock(CacheManager::class),
            $this->createMock(ConnectionPool::class),
            $this->createMock(PersistenceManager::class),
            $this->createMock(SmallAdsRepository::class),
            $this->createMock(StorageRepository::class),
        );
    }

    private function buildCommandArgs(): array
    {
        return [
            $this->createMock(CacheManager::class),
            $this->get(ConnectionPool::class),
            $this->get(PersistenceManager::class),
            $this->get(SmallAdsRepository::class),
            $this->createMock(StorageRepository::class),
        ];
    }
}
