<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use Vd\VdSmallAds\Command\ImportCommand;
use Vd\VdSmallAds\Domain\Repository\SmallAdsRepository;

use function putenv;

final class ImportCommandTest extends TestCase
{
    private CommandTester $commandTester;

    #[Test]
    public function executeThrowsWhenWebserviceUrlIsNotConfigured(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(1651242749);

        $this->commandTester->execute([
            'fileStorageUid' => '1',
            'storagePid' => '1',
            'uploadFolder' => 'user_upload/'
        ]);
    }

    #[Test]
    public function executeThrowsWhenWebserviceUrlIsInvalid(): void
    {
        putenv('TYPO3_VDSMALLADS_SERVICE_URL=not-a-valid-url');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(1705654833);

        $this->commandTester->execute([
            'fileStorageUid' => '1',
            'storagePid' => '1',
            'uploadFolder' => 'user_upload/'
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        putenv('TYPO3_VDSMALLADS_SERVICE_URL');

        $command = new ImportCommand(
            $this->createMock(CacheManager::class),
            $this->createMock(ConnectionPool::class),
            $this->createMock(PersistenceManager::class),
            $this->createMock(SmallAdsRepository::class),
            $this->createMock(StorageRepository::class)
        );

        $this->commandTester = new CommandTester($command);
    }

    protected function tearDown(): void
    {
        putenv('TYPO3_VDSMALLADS_SERVICE_URL');

        parent::tearDown();
    }
}
