<?php

declare(strict_types=1);

namespace Vd\VdApprenticeship\Hooks;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;

use function trim;

class DataHandlerHook
{
    protected ConnectionPool $connection;

    public function __construct(ConnectionPool $connection)
    {
        $this->connection = $connection;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function processDatamap_afterDatabaseOperations(
        string $status,
        string $table,
        string $id,
        array $fields,
        DataHandler $dataHandler
    ): void {
        if ($table !== 'tx_vdapprenticeship_apprenticeship') {
            return;
        }

        $uid = (int)($status === 'new' ? $dataHandler->substNEWwithIDs[$id] : $id);
        $title = $this->getTitle($uid);

        if ($title === '') {
            return;
        }

        $this->connection
            ->getConnectionForTable($table)
            ->update(
                $table,
                [
                    'title' => $this->getTitle($uid)
                ],
                [
                    'uid' => $uid
                ]
            );
    }

    protected function getTitle(int $parent): string
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tt_address');
        $queryBuilder->getRestrictions()->removeAll();

        $titleParts = $queryBuilder
            ->select('company', 'first_name', 'last_name')
            ->from('tt_address')
            ->where(
                $queryBuilder->expr()->eq(
                    'parent',
                    $queryBuilder->createNamedParameter($parent, Connection::PARAM_INT)
                )
            )
            ->execute()
            ->fetchAssociative();

        if ($titleParts === false) {
            return '';
        }

        if ($titleParts['company'] !== '') {
            $companyPart = $titleParts['company'] . ', ';
        }

        return ($companyPart ?? '') . trim($titleParts['first_name'] . ' ' . $titleParts['last_name']);
    }
}
