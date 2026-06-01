<?php

declare(strict_types=1);

namespace Vd\VdWsprosecutor\Domain\Repository;

use DateTimeImmutable;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function str_replace;
use function strpos;

class OfficeHourRepository
{
    protected ConnectionPool $connection;

    public function __construct(ConnectionPool $connection)
    {
        $this->connection = $connection;
    }

    public function fetchAll(): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdwsprosecutor_domain_model_officehour');
        $queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $statement = $queryBuilder
            ->select(
                'end_shift',
                'end_shift_date_hour',
                'end_shift_day',
                'event',
                'magistrate_name',
                'office',
                'start_shift',
                'start_shift_date_hour',
                'start_shift_day',
                'substitute_name'
            )
            ->from('tx_vdwsprosecutor_domain_model_officehour')
            ->orderBy('start_shift', 'DESC')
            ->execute();

        $records = [];

        while ($rows = $statement->fetchAssociative()) {
            $records[] = $rows;
        }

        return $records;
    }

    public function fetchAllCurrentOffices(): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdwsprosecutor_domain_model_officehour');
        $queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $statement = $queryBuilder
            ->select(
                'end_shift_date',
                'end_shift_date_hour',
                'end_shift_hour',
                'event',
                'magistrate_name',
                'office',
                'start_shift_date_hour',
                'start_shift_hour'
            )
            ->from('tx_vdwsprosecutor_domain_model_officehour')
            ->where(
                $queryBuilder->expr()->gte(
                    'end_shift',
                    $queryBuilder->createNamedParameter(
                        (new DateTimeImmutable())->getTimestamp()
                    )
                ),
                $queryBuilder->expr()->lte(
                    'start_shift',
                    $queryBuilder->createNamedParameter(
                        (new DateTimeImmutable())->setTime(0, 0)->modify('+1 day')->getTimestamp()
                    )
                ),
                $queryBuilder->expr()->neq('office', $queryBuilder->createNamedParameter(''))
            )
            ->orderBy('start_shift')
            ->execute();

        $now = new DateTimeImmutable();
        $records = [];

        while ($rows = $statement->fetchAssociative()) {
            $startShift = DateTimeImmutable::createFromFormat('d.m.Y H:i', $rows['start_shift_date_hour']);
            $endShift = DateTimeImmutable::createFromFormat('d.m.Y H:i', $rows['end_shift_date_hour']);

            $currentDate = $now->format('d.m.Y');
            $currentHour = $now->format('H');

            if (
                $currentDate === $startShift->format('d.m.Y')
                && ((int)$currentHour - (int)$startShift->format('H')) < 0
            ) {
                $rows['starting_soon'] = true;
            } else {
                $rows['starting_soon'] = false;
            }

            if ($currentDate === $endShift->format('d.m.Y') && ((int)$currentHour - (int)$endShift->format('H')) < 0) {
                $rows['ending_soon'] = true;
            } else {
                $rows['ending_soon'] = false;
            }

            if (strpos($rows['office'], 'MP VD') === 0) {
                $records['MPVd'][str_replace('MP VD', 'Garde cantonale MP', $rows['office'])][] = $rows;
            } elseif (strpos($rows['office'], 'MP') === 0) {
                $records['MP'][$rows['office']][] = $rows;
            } elseif (strpos($rows['office'], 'TM') === 0) {
                $records['TM'][$rows['office']][] = $rows;
            } elseif (strpos($rows['office'], 'DIVAS') !== false) {
                $records['Divas'][$rows['office']][] = $rows;
            }
        }

        return $records;
    }

    public function fetchOneByExternalId(string $externalId): int
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdwsprosecutor_domain_model_officehour');
        $queryBuilder->getRestrictions()->removeAll();

        return (int)$queryBuilder
            ->select('uid')
            ->from('tx_vdwsprosecutor_domain_model_officehour')
            ->where($queryBuilder->expr()->eq('external_id', $queryBuilder->createNamedParameter($externalId)))
            ->execute()
            ->fetchOne();
    }

    public function fetchOutdatedRecords(): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdwsprosecutor_domain_model_officehour');
        $queryBuilder->getRestrictions()->removeAll();

        $statement = $queryBuilder
            ->select('uid')
            ->from('tx_vdwsprosecutor_domain_model_officehour')
            ->where(
                $queryBuilder->expr()->lt(
                    'end_shift',
                    $queryBuilder->createNamedParameter(
                        (new DateTimeImmutable())->setTime(0, 0)->getTimestamp(),
                        Connection::PARAM_INT
                    )
                )
            )
            ->execute();

        $records = [];

        while ($uid = $statement->fetchOne()) {
            $records[] = $uid;
        }

        return $records;
    }

    public function fetchPagesWithPlugin(): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tt_content');
        $queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $statement = $queryBuilder
            ->select('pid')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('list')),
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq(
                        'list_type',
                        $queryBuilder->createNamedParameter('vdwsprosecutor_officehourcurrent')
                    ),
                    $queryBuilder->expr()->eq(
                        'list_type',
                        $queryBuilder->createNamedParameter('vdwsprosecutor_officehourlist')
                    )
                )
            )
            ->groupBy('pid')
            ->execute();

        $records = [];

        while ($pid = $statement->fetchOne()) {
            $records[] = $pid;
        }

        return $records;
    }
}
