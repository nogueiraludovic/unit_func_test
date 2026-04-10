<?php

declare(strict_types=1);

namespace Vd\VdCore\Hooks;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_unique;
use function str_contains;
use function str_replace;

class DataHandlerHook
{
    public function clearAdditionalCache(array $parameters): void
    {
        $cacheTags = [];
        $tags = $GLOBALS['TYPO3_CONF_VARS']['EXT']['vd_core']['cacheTagToFlush'] ?? [];

        foreach ($tags as $tag) {
            if (isset($parameters['table']) === true && str_contains($parameters['table'], (string)$tag) === true) {
                $tagNamespace = str_replace('domain_model_', '', $parameters['table']);

                if (isset($parameters['uid']) === true) {
                    $cacheTags[] = $tagNamespace . '_uid_' . $parameters['uid'];
                }

                if (isset($parameters['uid_page']) === true) {
                    $cacheTags[] = $tagNamespace . '_pid_' . $parameters['uid_page'];
                }
            }
        }

        if ($cacheTags === []) {
            return;
        }

        GeneralUtility::makeInstance(CacheManager::class)->flushCachesInGroupByTags('pages', array_unique($cacheTags));
    }

    public function processDatamap_postProcessFieldArray(
        string $status,
        string $table,
        string $uid,
        array &$fields
    ): void {
        if ($status !== 'update' || $table !== 'tt_content') {
            return;
        }

        $record = BackendUtility::getRecord($table, $uid, 'CType');

        if (isset($fields['CType']) === false || $record['CType'] !== 'list') {
            return;
        }

        $fields['list_type'] = '';
        $fields['pi_flexform'] = null;
    }
}
