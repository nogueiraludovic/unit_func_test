<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_unique;
use function sprintf;

class RemoveOrphansCommand extends Command
{
    protected function configure(): void
    {
        $this->setDescription('Remove records that lost their relation to a prestation.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $tablesToClean = [
            'tx_vdprestations_domain_model_url' => [
                'legal_references',
                'related_pages'
            ],
            'tx_vdprestations_domain_model_accessmodality' => [
                'access_modalities'
            ]
        ];
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        foreach ($tablesToClean as $table => $tableFields) {
            foreach ($tableFields as $field) {
                $queryBuilder = $connectionPool->getQueryBuilderForTable('tx_vdprestations_domain_model_prestation');
                $queryBuilder->getRestrictions()->removeAll();

                $statement = $queryBuilder
                    ->addSelectLiteral($field)
                    ->from('tx_vdprestations_domain_model_prestation')
                    ->where($queryBuilder->expr()->neq($field, $queryBuilder->createNamedParameter('')))
                    ->execute();

                $usedRecordUids = '';

                while ($rows = $statement->fetchAssociative()) {
                    $usedRecordUids .= ',' . $rows[$field];
                }

                $qbTable = $connectionPool->getQueryBuilderForTable($table);
                $qbTable->getRestrictions()->removeAll();

                $qbTable
                    ->delete($table)
                    ->where(
                        $qbTable->expr()->notIn(
                            'uid',
                            $qbTable->createNamedParameter(
                                array_unique(GeneralUtility::trimExplode(',', $usedRecordUids, true)),
                                Connection::PARAM_INT_ARRAY
                            )
                        )
                    );

                if ($table === 'tx_vdprestations_domain_model_url') {
                    $qbTable->andWhere(
                        $qbTable->expr()->eq(
                            'fieldname',
                            $qbTable->createNamedParameter($field)
                        )
                    );
                    $qbTable->orWhere(
                        $qbTable->expr()->eq(
                            'fieldname',
                            $qbTable->createNamedParameter('')
                        )
                    );
                }

                $res = $qbTable->execute();

                if ($res !== false) {
                    $io->success(sprintf('%d orphan records from table `%s` have been deleted.', $res, $table));
                } else {
                    // @extensionScannerIgnoreLine
                    $io->error('Ooops something went wrong.');
                }
            }
        }

        return Command::SUCCESS;
    }
}
