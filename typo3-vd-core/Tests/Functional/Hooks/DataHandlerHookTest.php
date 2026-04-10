<?php

declare(strict_types=1);

namespace Vd\VdCore\Hooks;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class DataHandlerHookTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = [
        'core'
    ];

    #[Test]
    public function clearAdditionalCacheFlushesExpectedTags(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['vd_core']['cacheTagToFlush'] = [
            'tt_content'
        ];

        $cacheManager = $this->getMockBuilder(CacheManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['flushCachesInGroupByTags'])
            ->getMock();

        $cacheManager
            ->expects($this->once())
            ->method('flushCachesInGroupByTags')
            ->with(
                'pages',
                ['tt_content_uid_123']
            );

        GeneralUtility::setSingletonInstance(CacheManager::class, $cacheManager);

        (new DataHandlerHook($this->get(ConnectionPool::class)))->clearAdditionalCache([
            'table' => 'tt_content',
            'uid' => 123
        ]);
    }

    #[Test]
    public function clearAdditionalCacheFlushesPidTagWhenUidPageIsProvided(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['vd_core']['cacheTagToFlush'] = [
            'tt_content'
        ];

        $cacheManager = $this->getMockBuilder(CacheManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['flushCachesInGroupByTags'])
            ->getMock();

        $cacheManager
            ->expects($this->once())
            ->method('flushCachesInGroupByTags')
            ->with(
                'pages',
                ['tt_content_pid_456']
            );

        GeneralUtility::setSingletonInstance(CacheManager::class, $cacheManager);

        (new DataHandlerHook())->clearAdditionalCache([
            'table' => 'tt_content',
            'uid_page' => 456
        ]);
    }

    #[Test]
    public function clearAdditionalCacheReturnsEarlyWhenNoTagsMatch(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['vd_core']['cacheTagToFlush'] = [
            'pages'
        ];

        $cacheManager = $this->createMock(CacheManager::class);
        $cacheManager->expects($this->never())->method('flushCachesInGroupByTags');

        GeneralUtility::setSingletonInstance(CacheManager::class, $cacheManager);

        (new DataHandlerHook())->clearAdditionalCache([
            'table' => 'tt_content',
            'uid' => 123
        ]);

        $this->assertTrue(true);
    }

    #[Test]
    public function processDatamapPostProcessFieldArrayResetsListFields(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/DataHandlerHookTest.csv');

        $fields = [
            'CType' => 'list'
        ];

        (new DataHandlerHook($this->get(ConnectionPool::class)))->processDatamap_postProcessFieldArray(
            'update',
            'tt_content',
            '100',
            $fields
        );

        $this->assertSame('', $fields['list_type']);
        $this->assertNull($fields['pi_flexform']);
    }

    #[Test]
    public function processDatamapPostProcessFieldArrayDoesNothingForNonList(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/DataHandlerHookTest.csv');

        $fields = [
            'CType' => 'text'
        ];

        (new DataHandlerHook($this->get(ConnectionPool::class)))->processDatamap_postProcessFieldArray(
            'update',
            'tt_content',
            '200',
            $fields
        );

        $this->assertSame(['CType' => 'text'], $fields);
    }

    #[Test]
    public function processDatamapPostProcessFieldArrayDoesNothingForOtherTables(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/DataHandlerHookTest.csv');

        $fields = [
            'CType' => 'text'
        ];

        (new DataHandlerHook($this->get(ConnectionPool::class)))->processDatamap_postProcessFieldArray(
            'new',
            'pages',
            '200',
            $fields
        );

        $this->assertSame(['CType' => 'text'], $fields);
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();

        parent::tearDown();
    }
}
