<?php

declare(strict_types=1);

namespace Vd\VdCore\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Configuration\ConfigurationManager;
use TYPO3\CMS\Core\Core\Environment;
use Vd\VdCore\Configuration\LocalConfigurationBuilder;

use function chgrp;
use function chown;
use function getenv;
use function is_array;
use function is_file;

final class GenerateLocalConfigurationCommand extends Command
{
    public function __construct(
        private readonly LocalConfigurationBuilder $builder,
        private readonly ConfigurationManager $configurationManager,
        private readonly ?string $configurationFile = null
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configurationFile = $this->configurationFile
            ?? (Environment::getConfigPath() . '/LocalConfiguration.dist.php');

        if (is_file($configurationFile) === false) {
            $output->writeln('<error>Missing file:</error> ' . $configurationFile);

            return self::FAILURE;
        }

        $configuration = require $configurationFile;

        if (is_array($configuration) === false) {
            $output->writeln('<error>Invalid dist configuration:</error> file must return an array');

            return self::FAILURE;
        }

        $this->configurationManager->writeLocalConfiguration($this->builder->build($configuration));

        if ((bool)getenv('IS_DDEV_PROJECT') === false) {
            chgrp(Environment::getPublicPath() . '/typo3conf/LocalConfiguration.php', 'apache');
            chown(Environment::getPublicPath() . '/typo3conf/LocalConfiguration.php', 'apache');
        }

        $output->writeln(
            '<info>Generated:</info> ' . Environment::getPublicPath() . '/typo3conf/LocalConfiguration.php'
        );

        return self::SUCCESS;
    }
}
