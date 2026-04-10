<?php

declare(strict_types=1);

namespace Vd\VdCore\DataProcessing;

use TYPO3\CMS\Core\PageTitle\PageTitleProviderManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

readonly class PageTitleProcessor implements DataProcessorInterface
{
    public function __construct(protected PageTitleProviderManager $pageTitleProviderManager)
    {
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $pageTitle = $this->pageTitleProviderManager->getTitle();

        if ($pageTitle === '') {
            return $processedData;
        }

        $processedData['pageTitle'] = $pageTitle;

        return $processedData;
    }
}
