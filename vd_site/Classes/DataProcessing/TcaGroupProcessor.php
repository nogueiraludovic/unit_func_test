<?php

declare(strict_types=1);

namespace Vd\VdSite\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Vd\VdCore\DataProcessing\TcaGroupProcessor as TcaGroupProcessorCore;
use Vd\VdCore\Utility\CacheUtility as CacheUtilityCore;
use Vd\VdPressreleases\Utility\CacheUtility as CacheUtilityPressReleases;

class TcaGroupProcessor extends TcaGroupProcessorCore
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $processedData = parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);

        foreach ($processedData['carouselItems'] as $record) {
            if (isset($record['data']['table']) === false) {
                continue;
            }

            switch ($record['data']['table']) {
                case 'tx_news_domain_model_news':
                    CacheUtilityCore::addCacheTagsForRecord($record['data']['table'], $record['data']);
                    break;
                case 'tx_vdpressreleases_domain_model_pressrelease':
                    CacheUtilityPressReleases::addCacheTagsForPressRelease($record['data']);
                    break;
            }
        }

        return $processedData;
    }
}
