<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SwitchStatusCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this->setDescription('Switch the status of a sale.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdojvencheres_domain_model_sale');
        $queryBuilder->getRestrictions()->removeAll();
        $queryBuilder
            ->update('tx_vdojvencheres_domain_model_sale')
            ->set('status', 1, true, Connection::PARAM_INT)
            ->where(
                $queryBuilder->expr()->lte(
                    'pub_date',
                    $queryBuilder->createNamedParameter(
                        GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp'),
                        Connection::PARAM_INT
                    )
                ),
                $queryBuilder->expr()->eq('status', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT))
            )
            ->execute();

        return Command::SUCCESS;
    }
}
