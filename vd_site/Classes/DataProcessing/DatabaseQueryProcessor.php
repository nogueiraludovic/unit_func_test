<?php

declare(strict_types=1);

namespace Vd\VdSite\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor as DatabaseQueryProcessorParent;
use Vd\VdCore\Utility\CacheUtility as CacheUtilityCore;
use Vd\VdPressreleases\Utility\CacheUtility as CacheUtilityPressReleases;

class DatabaseQueryProcessor extends DatabaseQueryProcessorParent
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        if (
            isset($processorConfiguration['if.']) === true
            && $cObj->checkIf($processorConfiguration['if.']) === false
        ) {
            return $processedData;
        }

        $processedData = parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);

        if (
            $processedData['data']['type'] !== 'news'
            && $processedData['data']['type'] !== 'press_releases'
            && $processedData['data']['type'] !== 'records'
        ) {
            return $processedData;
        }

        foreach ($processedData['carouselItems'] as $record) {
            switch ($processorConfiguration['table']) {
                case 'tx_news_domain_model_news':
                    CacheUtilityCore::addCacheTagsForRecord($processorConfiguration['table'], $record['data']);
                    break;
                case 'tx_vdpressreleases_domain_model_pressrelease':
                    CacheUtilityPressReleases::addCacheTagsForPressRelease($record['data']);
                    break;
            }
        }

        return $processedData;
    }
}
