<?php

declare(strict_types=1);

namespace Vd\VdNews\Database;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RecordRepository
{
    protected ConnectionPool $connection;

    public function __construct()
    {
        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class);
    }

    public function countNewsByTypeAndUid(int $type, int $uid): int
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_news_domain_model_news');

        return (int)$queryBuilder
            ->count('*')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)),
                    $queryBuilder->expr()->neq(
                        'type',
                        $queryBuilder->createNamedParameter($type, Connection::PARAM_INT)
                    )
                )
            )
            ->execute()
            ->fetchOne();
    }

    public function fetchByUid(int $uid, $respectEnableFields = true): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_news_domain_model_news');

        if ($respectEnableFields === true) {
            $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));
        } else {
            $queryBuilder->getRestrictions()->removeAll();
        }

        $record = $queryBuilder
            ->select('*')
            ->from('tx_news_domain_model_news')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)))
            ->execute()
            ->fetchAssociative();

        return $record ?: [];
    }

    public function fetchFlexFormByPid(int $pid): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tt_content');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $record = $queryBuilder
            ->select('pi_flexform')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter('news_pi1')),
                        $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter('solr_pi_results'))
                    ),
                    $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pid, Connection::PARAM_INT))
                )
            )
            ->execute()
            ->fetchOne();

        if ($record === false) {
            return [];
        }

        return GeneralUtility::makeInstance(FlexFormService::class)->convertFlexFormContentToArray($record);
    }

    public function fetchPagesListByPid(int $pid): string
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tt_content');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $record = $queryBuilder
            ->select('pages')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('carousel')),
                    $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pid, Connection::PARAM_INT)),
                    $queryBuilder->expr()->eq('type', $queryBuilder->createNamedParameter('news'))
                )
            )
            ->execute()
            ->fetchOne();

        if ($record === false || $record === null) {
            return '';
        }

        return $record;
    }
}
