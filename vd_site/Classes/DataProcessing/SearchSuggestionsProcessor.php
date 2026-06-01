<?php

declare(strict_types=1);

namespace Vd\VdSite\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

use function array_unique;

class SearchSuggestionsProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $processedData['suggestions'] = array_unique(
            GeneralUtility::trimExplode(',', $cObj->data[0]['suggestions'], true)
        );

        return $processedData;
    }
}
