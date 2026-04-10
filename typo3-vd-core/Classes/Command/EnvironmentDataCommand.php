<?php

declare(strict_types=1);

namespace Vd\VdCore\Command;

use Composer\InstalledVersions;
/** @noinspection PhpDeprecationInspection */
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function is_dir;
use function rtrim;
use function time;

class EnvironmentDataCommand extends Command
{
    public function __construct(protected string $environmentPath, protected Registry $registry)
    {
        parent::__construct();
    }

    #[CodeCoverageIgnore]
    protected function configure(): void
    {
        $this->addOption(
            'debug',
            'd',
            InputOption::VALUE_OPTIONAL,
            'Enable debug mode, will add entries in database even if not in production.',
            0
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ((string)Environment::getContext() !== 'Production' && (bool)$input->getOption('debug') === false) {
            return Command::SUCCESS;
        }

        $rootPackage = InstalledVersions::getRootPackage();

        if ((string)$rootPackage['pretty_version'] === '') {
            return Command::SUCCESS;
        }

        $time = time();

        $this->registry->set('vd_core', 'prodDatabaseDate', $time);
        $this->registry->set('vd_core', 'prodProjectVersion', $rootPackage['pretty_version']);

        $environmentPath = Environment::getPublicPath() . '/' . rtrim($this->environmentPath, '/');

        if (is_dir($environmentPath) === false) {
            GeneralUtility::mkdir_deep($environmentPath);
        }

        GeneralUtility::writeFile($environmentPath . '/files-refresh.txt', $time);

        return Command::SUCCESS;
    }
}
