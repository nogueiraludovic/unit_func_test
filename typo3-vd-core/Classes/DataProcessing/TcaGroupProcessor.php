<?php

declare(strict_types=1);

namespace Vd\VdCore\DataProcessing;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\RelationHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class TcaGroupProcessor implements DataProcessorInterface
{
    protected ContentDataProcessor $contentDataProcessor;

    public function __construct(private readonly ConnectionPool $connection)
    {
        $this->contentDataProcessor = GeneralUtility::makeInstance(ContentDataProcessor::class);
    }

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

        if ($cObj->data[$processorConfiguration['field']] === '') {
            return $processedData;
        }

        $relationHandler = GeneralUtility::makeInstance(RelationHandler::class);
        $relationHandler->start($cObj->data[$processorConfiguration['field']], $processorConfiguration['allowed']);

        $records = [];

        foreach ($relationHandler->itemArray as $key => $record) {
            $queryBuilder = $this->connection->getQueryBuilderForTable($record['table']);

            $statement = $queryBuilder
                ->select('*')
                ->from($record['table'])
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($record['id'], Connection::PARAM_INT)
                    )
                )
                ->executeQuery();

            while ($rows = $statement->fetchAssociative()) {
                $records[$key] = $rows;
                $records[$key]['table'] = $record['table'];
            }
        }

        $processedRecordVariables = [];

        foreach ($records as $key => $record) {
            $recordContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $recordContentObjectRenderer->start($record, $record['table']);

            $processedRecordVariables[$key] = [
                'data' => $record
            ];
            $processedRecordVariables[$key] = $this->contentDataProcessor->process(
                $recordContentObjectRenderer,
                $processorConfiguration,
                $processedRecordVariables[$key]
            );
        }

        if ($processedRecordVariables === []) {
            return $processedData;
        }

        $processedData[$cObj->stdWrapValue('as', $processorConfiguration, 'records')] = $processedRecordVariables;

        return $processedData;
    }
}
