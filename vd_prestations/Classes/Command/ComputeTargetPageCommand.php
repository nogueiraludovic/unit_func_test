<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

use function count;

class ComputeTargetPageCommand extends Command
{
    protected function configure(): void
    {
        $this->setDescription('Update fields "domain_target_page" and "theme_target_page" for all prestations.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Start executing...');

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        $queryBuilder = $connectionPool->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll();

        $records = $queryBuilder
            ->select('pi_flexform', 'pid')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('list')),
                $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter('vdprestations_pi1'))
            )
            ->execute()
            ->fetchAllAssociative();

        if ($records === false) {
            return Command::SUCCESS;
        }

        $connection = $connectionPool->getConnectionForTable('tx_vdprestations_domain_model_prestation');
        $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);

        foreach ($records as $record) {
            $flexForm = $flexFormService->convertFlexFormContentToArray($record['pi_flexform']);

            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->getRestrictions()->removeAll();

            $prestations = $queryBuilder
                ->select('external_id')
                ->from('tx_vdprestations_domain_model_prestation')
                ->where(
                    $queryBuilder->expr()->eq(
                        'domain_id',
                        $queryBuilder->createNamedParameter($flexForm['settings']['domain'])
                    ),
                    $queryBuilder->expr()->eq(
                        'theme_id',
                        $queryBuilder->createNamedParameter($flexForm['settings']['theme'])
                    )
                )
                ->execute()
                ->fetchAllAssociative();

            if (count($prestations) === 0) {
                continue;
            }

            $pages = GeneralUtility::makeInstance(RootlineUtility::class, $record['pid'])->get();

            foreach ($pages as $key => $page) {
                if ($page['uid'] !== 1002722) {
                    continue;
                }

                $domain = $pages[$key + 1]['uid'];
                $theme = $pages[$key + 2]['uid'];
                break;
            }

            foreach ($prestations as $prestation) {
                $connection->update(
                    'tx_vdprestations_domain_model_prestation',
                    [
                        'domain_target_page' => $domain ?? 0,
                        'theme_target_page' => $theme ?? 0
                    ],
                    [
                        'external_id' =>  $prestation['external_id']
                    ]
                );
            }
        }

        $io->success('Done');

        return Command::SUCCESS;
    }
}
