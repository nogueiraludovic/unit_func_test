<?php

declare(strict_types=1);

namespace Vd\VdSite\Command\Sitemap;

use Symfony\Component\Console\Command\Command;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractSitemapCommand extends Command
{
    protected Connection $connection;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_vdsite_queueitem');
    }
}
