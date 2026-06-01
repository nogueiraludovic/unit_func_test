<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdSite\Updates;

use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class DeleteAccessibilityMenuUpdate implements UpgradeWizardInterface
{
    public function executeUpdate(): bool
    {
        if (Environment::isCli() === false) {
            Bootstrap::initializeBackendUser();
            Bootstrap::initializeBackendAuthentication();
            Bootstrap::initializeLanguageObject();
        }

        $GLOBALS['BE_USER']->uc['recursiveDelete'] = true;

        $cmd['pages'][2000032]['delete'] = 1;

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->deleteTree = true;
        $dataHandler->start([], $cmd);
        $dataHandler->process_cmdmap();

        return true;
    }

    public function getDescription(): string
    {
        return 'Delete accessibility menu page "2000032" and sub-pages/content.';
    }

    public function getIdentifier(): string
    {
        return 'vdSiteDeleteAccessibilityMenu';
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }

    public function getTitle(): string
    {
        return 'vd_site: Delete accessibility menu PID: "2000032"';
    }

    public function updateNecessary(): bool
    {
        return true;
    }
}
