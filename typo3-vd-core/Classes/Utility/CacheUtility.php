<?php

declare(strict_types=1);

namespace Vd\VdCore\Utility;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

use function array_unique;
use function is_array;

class CacheUtility
{
    public static function addCacheTagToFlush(string $cacheTag): void
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['vd_core']['cacheTagToFlush']) === false) {
            $GLOBALS['TYPO3_CONF_VARS']['EXT']['vd_core']['cacheTagToFlush'] = [];
        }

        $GLOBALS['TYPO3_CONF_VARS']['EXT']['vd_core']['cacheTagToFlush'][$cacheTag] = $cacheTag;
    }

    public static function addCacheTagsForPages(string $tagNamespace, array $storagePids): void
    {
        $cacheTags = [];

        foreach ($storagePids as $storagePid) {
            $cacheTags[] = $tagNamespace . '_pid_' . $storagePid;
        }

        self::addCacheTags($cacheTags);
    }

    public static function addCacheTagsForRecord(string $tagNamespace, $record): void
    {
        $isArray = is_array($record);

        $localizedUid = $isArray === true ? $record['_LOCALIZED_UID'] ?? 0 : $record->_getProperty('_localizedUid');
        $uid = $isArray === true ? $record['uid'] : $record->getUid();

        $cacheTags[] = $tagNamespace . '_uid_' . $uid;

        if ((int)$localizedUid > 0) {
            $cacheTags[] = $tagNamespace . '_uid_' . $localizedUid;
        }

        self::addCacheTags(array_unique($cacheTags));
    }

    public static function addCacheTagsForRecords(string $tagNamespace, $records): void
    {
        foreach ($records as $record) {
            self::addCacheTagsForRecord($tagNamespace, $record);
        }
    }

    protected static function addCacheTags(array $cacheTags): void
    {
        if ($cacheTags === []) {
            return;
        }

        self::getFrontendController()->addCacheTags(array_unique($cacheTags));
    }

    protected static function getFrontendController(): TyposcriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
