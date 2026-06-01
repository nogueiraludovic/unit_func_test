<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Command;

use Symfony\Component\Console\Command\Command;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractCommand extends Command
{
    protected ?ConnectionPool $connection = null;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class);
    }
}
