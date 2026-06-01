<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\QueryGenerator;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function getenv;
use function rtrim;
use function str_replace;

class MemberLinksCommand extends Command
{
    protected ConnectionPool $connection;
    protected QueryGenerator $queryGenerator;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class);
        $this->queryGenerator = GeneralUtility::makeInstance(QueryGenerator::class);
    }

    protected function configure(): void
    {
        $this->setDescription('Clean old links to member to new ones for the EXT:vd_safarinet.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pages = GeneralUtility::intExplode(',', $this->queryGenerator->getTreeList(1005267, 250));

        $queryBuilder = $this->connection->getQueryBuilderForTable('pages');
        $queryBuilder->getRestrictions()->removeAll();

        foreach ($pages as $page) {
            $statement = $queryBuilder
                ->select('uid', 'url')
                ->from('pages')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($page, Connection::PARAM_INT)),
                    $queryBuilder->expr()->isNotNull('url'),
                    $queryBuilder->expr()->like(
                        'url',
                        $queryBuilder->createNamedParameter(
                            '%https://www.vd.ch/toutes-les-autorites/grand-conseil/depute-e-s/membre-du-grand-conseil/id%'
                        )
                    )
                )
                ->execute();

            while ($rows = $statement->fetchAssociative()) {
                $this->connection
                    ->getConnectionForTable('pages')
                    ->update(
                        'pages',
                        [
                            'url' => rtrim(
                                str_replace(
                                    'https://www.vd.ch/toutes-les-autorites/grand-conseil/depute-e-s/membre-du-grand-conseil/id',
                                    getenv('DGNSI_PUBLIC_URL') . 'gc/depute-e-s/membre-du-grand-conseil/membre',
                                    $rows['url']
                                ),
                                '/'
                            )
                        ],
                        [
                            'uid' => $rows['uid']
                        ]
                    );
            }
        }

        return Command::SUCCESS;
    }
}
