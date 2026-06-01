<?php

declare(strict_types=1);

namespace Vd\VdNews\DataProcessing;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Vd\VdNews\Database\RecordRepository;

use function count;

class NewsProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $arguments = $this->getRequest()->getQueryParams();

        if ($arguments['tx_vdnews_pi1']['news'] === null && $arguments['tx_vdnews_pi1']['news_preview'] === null) {
            return $processedData;
        }

        $recordRepository = GeneralUtility::makeInstance(RecordRepository::class);

        if ($arguments['tx_vdnews_pi1']['news'] !== null) {
            $news = $recordRepository->fetchByUid((int)$arguments['tx_vdnews_pi1']['news']);
            $respectEnableFields = true;
        } elseif ($arguments['tx_vdnews_pi1']['news_preview'] !== null) {
            $news = $recordRepository->fetchByUid((int)$arguments['tx_vdnews_pi1']['news_preview'], false);
            $respectEnableFields = false;
        } else {
            return $processedData;
        }

        if ((int)$news['type'] === 3) {
            $news = $recordRepository->fetchByUid((int)$news['selected_article'], $respectEnableFields);
        }

        if (count($news) === 0) {
            return $processedData;
        }

        $processedData['newsData'] = $news;

        return $processedData;
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
