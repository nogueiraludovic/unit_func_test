<?php

declare(strict_types=1);

namespace Vd\VdCore\SystemInformation;

use Composer\InstalledVersions;
use DateTime;
use TYPO3\CMS\Backend\Backend\Event\SystemInformationToolbarCollectorEvent;
use TYPO3\CMS\Backend\Toolbar\Enumeration\InformationStatus;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Registry;

use function file_get_contents;
use function is_file;
use function rtrim;

final readonly class ToolbarItemProvider
{
    public function __construct(private string $environmentPath, private Registry $registry)
    {
    }

    public function __invoke(SystemInformationToolbarCollectorEvent $event): void
    {
        $toolbarItem = $event->getToolbarItem();

        $currentProjectVersion = (string)InstalledVersions::getRootPackage()['pretty_version'];

        if ($currentProjectVersion !== '') {
            $toolbarItem->addSystemInformation(
                'DGNSI version',
                $currentProjectVersion,
                'information-git',
                InformationStatus::STATUS_OK
            );
        }

        if ((string)Environment::getContext() === 'Production') {
            return;
        }

        $prodProjectVersion = (string)$this->registry->get('vd_core', 'prodProjectVersion');

        if ($prodProjectVersion !== '') {
            $toolbarItem->addSystemInformation('[PROD] DGNSI version', $prodProjectVersion, 'information-git');
        }

        $prodDatabaseTimestamp = (int)$this->registry->get('vd_core', 'prodDatabaseDate');

        if ($prodDatabaseTimestamp !== 0) {
            $toolbarItem->addSystemInformation(
                '[PROD] Database refresh',
                (new DateTime())->setTimestamp($prodDatabaseTimestamp)->format('d-m-Y H:i'),
                'information-database'
            );
        }

        $filesRefresh = Environment::getPublicPath() . '/' . rtrim($this->environmentPath, '/') . '/files-refresh.txt';

        if (is_file($filesRefresh) === false) {
            return;
        }

        $prodFilesTimestamp = (int)file_get_contents($filesRefresh);

        if ($prodFilesTimestamp !== 0) {
            $toolbarItem->addSystemInformation(
                '[PROD] Files refresh',
                (new DateTime())->setTimestamp($prodFilesTimestamp)->format('d-m-Y H:i'),
                'actions-file'
            );
        }
    }
}
