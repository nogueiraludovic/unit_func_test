<?php

declare(strict_types=1);

namespace Vd\VdNews\Hooks;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DataHandlerHook
{
    public function processDatamap_afterDatabaseOperations(
        string $status,
        string $table,
        string $id,
        array $fields,
        DataHandler $dataHandler
    ): void {
        if ($status !== 'new' || $table !== 'tx_news_domain_model_news') {
            return;
        }

        $uid = (int)$dataHandler->substNEWwithIDs[$id];

        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($table)
            ->update(
                $table,
                [
                    'path_segment' => $uid
                        . ($GLOBALS['TCA'][$table]['columns']['path_segment']['config']['generatorOptions']['fieldSeparator'] ?? '/')
                        . $fields['path_segment']
                ],
                [
                    'uid' => $uid
                ]
            );
    }
}
