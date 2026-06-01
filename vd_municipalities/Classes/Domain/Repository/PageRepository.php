<?php

declare(strict_types=1);

namespace Vd\VdMunicipalities\Domain\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;

class PageRepository
{
    protected ConnectionPool $connection;

    public function __construct(ConnectionPool $connection)
    {
        $this->connection = $connection;
    }

    public function findByInstitution(int $institution): array
    {
        $queryBuilder = $this->connection ->getQueryBuilderForTable('pages');

        $records = $queryBuilder
            ->select('*')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq(
                    'tx_vdmunicipalitiessearch_institution',
                    $queryBuilder->createNamedParameter($institution, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->gt('uid', 1000000)
            )
            ->execute()
            ->fetchAllAssociative();

        return $records ?: [];
    }
}
