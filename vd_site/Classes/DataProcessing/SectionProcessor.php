<?php

declare(strict_types=1);

namespace Vd\VdSite\DataProcessing;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

use function usort;

class SectionProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $processedContent = [];

        foreach ($processedData['content'] as $content) {
            if ((int)$content['data']['header_layout'] === 100) {
                continue;
            }

            if ((bool)$content['data']['sectionIndex'] === false) {
                continue;
            }

            if ($content['data']['tx_container_parent'] > 0) {
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getQueryBuilderForTable('tt_content');
                $queryBuilder->getRestrictions()->removeAll();

                $isHidden = $queryBuilder
                    ->select('hidden')
                    ->from('tt_content')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter(
                                $content['data']['tx_container_parent'],
                                Connection::PARAM_INT
                            )
                        )
                    )
                    ->execute()
                    ->fetchOne();

                if ((bool)$isHidden === true) {
                    continue;
                }
            }

            $content['data']['sorting'] = $this->resolveSorting($content, $processedData['content']);

            $processedContent[] = $content;
        }

        usort($processedContent, static function (array $a, array $b): bool {
            return $a['data']['sorting'] > $b['data']['sorting'];
        });

        $processedData['content'] = $processedContent;

        return $processedData;
    }

    protected function resolveSorting(array $content, array $elements): int
    {
        if ($content['data']['colPos'] !== -1) {
            return $content['data']['sorting'] * 10000;
        }

        $sorting = $content['data']['sorting'];

        foreach ($elements as $element) {
            if ($element['data']['uid'] !== $content['data']['tx_container_parent']) {
                continue;
            }

            $sorting += $element['data']['sorting'] * 10000;
        }

        return $sorting;
    }
}
