<?php

declare(strict_types=1);

namespace Vd\VdSite\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function getenv;
use function parse_url;

use const PHP_URL_HOST;

class UseCorrectSiteInRedirectionCommand extends Command
{
    protected QueryBuilder $queryBuilder;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_redirect');
        $this->queryBuilder->getRestrictions()->removeAll();
    }

    protected function configure(): void
    {
        $this->setDescription('Use correct site in redirection.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $beSourceHost = ['validation.portail.etat-de-vaud.ch'];
        $feSourceHost = ['www.vd.ch'];
        $replaceHost = parse_url((string)getenv('DGNSI_PUBLIC_URL'), PHP_URL_HOST);

        switch ((string)Environment::getContext()) {
            case 'Development/DDEV':
                $beSourceHost = $feSourceHost = [
                    'i2.vd.ch',
                    'int.vd.ch',
                    'valid.vd.ch',
                    'www.vd.ch'
                ];
                break;
            case 'Production':
                return Command::SUCCESS;
        }

        $this->queryBuilder
            ->update('sys_redirect')
            ->set('source_host', $replaceHost)
            ->where(
                $this->queryBuilder->expr()->orX(
                    $this->queryBuilder->expr()->in(
                        'source_host',
                        $this->queryBuilder->createNamedParameter($beSourceHost, Connection::PARAM_STR_ARRAY)
                    ),
                    $this->queryBuilder->expr()->in(
                        'source_host',
                        $this->queryBuilder->createNamedParameter($feSourceHost, Connection::PARAM_STR_ARRAY)
                    )
                )
            )
            ->execute();
        $this->updateSpecificRedirect();

        $output->writeln('<info>Updating redirections for source host "' . $replaceHost . '"</info>');

        return Command::SUCCESS;
    }

    protected function updateSpecificRedirect(): void
    {
        if ((string)Environment::getContext() === 'Production') {
            return;
        }

        $this->queryBuilder
            ->update('sys_redirect')
            ->set('target', 'https://validation.portail.etat-de-vaud.ch/iam/accueil/')
            ->where($this->queryBuilder->expr()->eq('source_path', $this->queryBuilder->createNamedParameter('/iam')))
            ->execute();
    }
}
