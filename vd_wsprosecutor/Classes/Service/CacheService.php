<?php

declare(strict_types=1);

namespace Vd\VdWsprosecutor\Service;

use TYPO3\CMS\Core\Cache\CacheManager;
use Vd\VdWsprosecutor\Domain\Repository\OfficeHourRepository;

use function array_map;

class CacheService
{
    protected CacheManager $cacheManager;
    protected OfficeHourRepository $officeHourRepository;

    public function __construct(CacheManager $cacheManager, OfficeHourRepository $officeHourRepository)
    {
        $this->cacheManager = $cacheManager;
        $this->officeHourRepository = $officeHourRepository;
    }

    public function flushCachesInPagesByTags(): void
    {
        $this->cacheManager->flushCachesInGroupByTags(
            'pages',
            array_map(
                static function ($pageWithPlugin): string {
                    return 'pageId_' . $pageWithPlugin;
                },
                $this->officeHourRepository->fetchPagesWithPlugin()
            )
        );
    }
}
