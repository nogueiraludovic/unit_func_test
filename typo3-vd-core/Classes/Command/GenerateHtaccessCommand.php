<?php

declare(strict_types=1);

namespace Vd\VdCore\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function chgrp;
use function chown;
use function file_get_contents;
use function getenv;
use function in_array;
use function preg_replace_callback;
use function str_replace;
use function str_starts_with;
use function substr;

final class GenerateHtaccessCommand extends Command
{
    private bool $hasError = false;
    private array $hostVariables = [
        'DGNSI_APP_HOST',
        'DGNSI_APP_SECURED_CONTEXT',
        'DGNSI_BACKEND_URL',
        'DGNSI_LDAP_HOST',
        'DGNSI_PUBLIC_URL',
        'TYPO3_INSTALL_DB_HOST',
        'TYPO3_VDSITE_URL'
    ];
    private SymfonyStyle $io;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $htaccessFile = Environment::getConfigPath() . '/.htaccess.dist';
        $htaccessContent = @file_get_contents($htaccessFile);

        if ($htaccessContent !== false) {
            $htaccessContent = preg_replace_callback('/%%%(.*)%%%/', [$this, 'replace'], $htaccessContent);

            $this->hasError = GeneralUtility::writeFile(
                Environment::getProjectPath() . '/htdocs/.htaccess',
                $htaccessContent
            ) === false
            || $this->hasError === true;

            if ((bool)getenv('IS_DDEV_PROJECT') === false) {
                chgrp(Environment::getProjectPath() . '/htdocs/.htaccess', 'dev-typo3');
                chown(Environment::getProjectPath() . '/htdocs/.htaccess', 'exploitation');
            }

            if ($this->hasError === false) {
                $this->io->writeln('<info>Generated:</info> ' . Environment::getPublicPath() . '/.htaccess');
            }
        } else {
            $this->io->writeln('<error>Missing file:</error> ' . $htaccessFile);
        }

        return Command::SUCCESS;
    }

    private function replace(array $matches): string
    {
        $returnValue = $matches[1];

        if (str_starts_with((string)$returnValue, 'ENV:') === false) {
            return $returnValue;
        }

        $env = substr((string)$returnValue, 4);
        $returnValue = (string)getenv($env);

        if ($returnValue !== '' && in_array($env, $this->hostVariables, true) === true) {
            return str_replace('.', '\\.', $returnValue);
        }

        $this->hasError = true;
        $this->io->writeln('<error>Missing environment variable:</error> ' . $env);

        return '';
    }
}
