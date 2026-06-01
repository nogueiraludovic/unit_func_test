<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdCore\Utility\CacheUtility as CacheUtilityCore;
use Vd\VdPressreleases\Domain\Repository\PressReleaseRepository;

use function is_array;

class CacheUtility
{
    public static function addCacheTagsForPressRelease($pressRelease): void
    {
        if (is_array($pressRelease) === true) {
            $pressRelease = GeneralUtility::makeInstance(PressReleaseRepository::class)
                ->findByUid($pressRelease['uid'], false, false);
        }

        CacheUtilityCore::addCacheTagsForRecord('tx_vdpressreleases_pressrelease', $pressRelease);
        CacheUtilityCore::addCacheTagsForRecord('tx_vdpressreleases_pressreleasetype', $pressRelease->getType());

        CacheUtilityCore::addCacheTagsForRecords('tx_vdpressreleases_contact', $pressRelease->getContacts());
        CacheUtilityCore::addCacheTagsForRecords('tx_vdpressreleases_link', $pressRelease->getAdditionalContents());
        CacheUtilityCore::addCacheTagsForRecords('tx_vdpressreleases_partnersource', $pressRelease->getPartnerSources());
    }
}
