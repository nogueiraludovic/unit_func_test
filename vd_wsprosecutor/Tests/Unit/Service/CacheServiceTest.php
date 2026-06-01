<?php

declare(strict_types=1);

namespace Vd\VdWsprosecutor\Tests\Unit\Service;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdWsprosecutor\Domain\Repository\OfficeHourRepository;
use Vd\VdWsprosecutor\Service\CacheService;

final class CacheServiceTest extends UnitTestCase
{
    public function testFlushCachesInPagesByTags(): void
    {
        // Mock the OfficeHourRepository
        $officeHourRepositoryMock = $this->createMock(OfficeHourRepository::class);
        $officeHourRepositoryMock->method('fetchPagesWithPlugin')->willReturn([1, 2, 3]);

        // Mock the CacheManager
        $cacheManagerMock = $this->createMock(CacheManager::class);
        $cacheManagerMock->expects($this->once()) // Expect it to be called once
        ->method('flushCachesInGroupByTags')
            ->with(
                'pages',
                ['pageId_1', 'pageId_2', 'pageId_3']
            );

        // Create the CacheService instance with mocked dependencies
        $cacheService = new CacheService($cacheManagerMock, $officeHourRepositoryMock);

        // Call the method to test
        $cacheService->flushCachesInPagesByTags();
    }
}
