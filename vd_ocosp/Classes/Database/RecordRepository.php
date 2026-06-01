<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Database;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RecordRepository
{
    protected ConnectionPool $connection;

    public function __construct()
    {
        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class);
    }

    public function fetchManyToManyRelation(string $table, string $mmTable, int $uid, bool $opposite = false): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable($table);
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        if ($opposite === true) {
            $foreignField = 'uid_local';
            $localField = 'uid_foreign';
        } else {
            $foreignField = 'uid_foreign';
            $localField = 'uid_local';
        }

        $records = $queryBuilder
            ->select('*')
            ->from($table)
            ->leftJoin(
                $table,
                $mmTable,
                $mmTable,
                $queryBuilder->expr()->eq(
                    $mmTable . '.' . $localField,
                    $queryBuilder->quoteIdentifier($table . '.uid')
                )
            )
            ->where(
                $queryBuilder->expr()->eq(
                    $mmTable . '.' . $foreignField,
                    $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)
                )
            )
            ->execute()
            ->fetchAllAssociative();

        return $records ?: [];
    }

    public function fetchOneToOneRelation(string $table, string $field, int $uid): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable($table);
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $records = $queryBuilder
            ->select('*')
            ->from($table)
            ->where(
                $queryBuilder->expr()->eq($field, $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT))
            )
            ->execute()
            ->fetchAllAssociative();

        return $records ?: [];
    }
}
