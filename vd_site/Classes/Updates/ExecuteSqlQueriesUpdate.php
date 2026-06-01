<?php

namespace Vd\VdSite\Updates;

use Doctrine\DBAL\Exception;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;
use TYPO3\CMS\Install\Updates\Confirmation;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

use function array_merge;
use function file;
use function str_starts_with;

class ExecuteSqlQueriesUpdate implements UpgradeWizardInterface, ConfirmableInterface, ChattyInterface
{
    use LoggerAwareTrait;

    protected ConnectionPool $connectionPool;
    protected string $insertFile = '';
    protected OutputInterface $output;
    protected string $updateFile = '';

    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
        $this->insertFile = ExtensionManagementUtility::extPath('vd_site') . '/Resources/Private/Sql/update.sql';
        $this->updateFile = ExtensionManagementUtility::extPath('vd_site') . '/Resources/Private/Sql/insert.sql';

        if (($this->logger instanceof LoggerInterface) === false) {
            $this->setLogger(GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__));
        }
    }

    public function executeQueries(string $file): void
    {
        $connection = $this->connectionPool->getConnectionByName('Default');
        $queries = $this->getQueries($file);

        foreach ($queries as $query) {
            try {
                $connection->executeQuery($query);
                $this->logger->notice('Query executed successfully: ' . $query);
            } catch (Exception $e) {
                $this->output->writeln('<error>Error executing query : ' . $query . ', error: ' . $e->getMessage() . '</error>');
                // @extensionScannerIgnoreLine
                $this->logger->error('Error executing query : ' . $query, ['error' => $e->getMessage(), 'exception' => $e, 'file' => $file]);
            }
        }
    }

    public function executeUpdate(): bool
    {
        $this->executeQueries($this->updateFile);
        $this->executeQueries($this->insertFile);

        return true;
    }

    public function getConfirmation(): Confirmation
    {
        return new Confirmation(
            'Are you sure?',
            'Are you sure this queries weren\'t already executed?' . "\n\n" .
            implode("\n\n", array_merge($this->getQueries($this->updateFile), $this->getQueries($this->insertFile))),
            false
        );
    }

    public function getDescription(): string
    {
        return 'Execute a batch of custom SQL queries.';
    }

    public function getIdentifier(): string
    {
        return 'vdSiteExecuteSqlQueries';
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }

    public function getQueries(string $file): array
    {
        $queriesInFile = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $queries = [];

        foreach ($queriesInFile as $query) {
            if (str_starts_with($query, '--') === false) {
                $queries[] = $query;
            }
        }

        return $queries;
    }

    public function getTitle(): string
    {
        return 'vd_site: Execute SQL queries';
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    public function updateNecessary(): bool
    {
        return true;
    }

}
