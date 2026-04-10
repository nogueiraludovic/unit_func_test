<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdCore\Hooks;

use TYPO3\CMS\Backend\Controller\PageLayoutController;
use TYPO3\CMS\Backend\Module\ModuleLoader;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;

use function is_array;

readonly class PageLayoutControllerHook
{
    public function __construct(protected ModuleLoader $moduleLoader)
    {
        $this->moduleLoader->load($GLOBALS['TBE_MODULES']);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function render(array $_, PageLayoutController $pageLayoutController): string
    {
        // @extensionScannerIgnoreLine
        $page = BackendUtility::getRecord('pages', $pageLayoutController->id, 'doktype');

        if ($page['doktype'] !== PageRepository::DOKTYPE_SYSFOLDER) {
            return '';
        }

        $modules = $this->moduleLoader->modules;

        if (is_array($modules['web']['sub']['list']) === true) {
            /** @noinspection JSUnresolvedReference */
            return '<script>top.TYPO3.ModuleMenu.App.showModule("web_list", true);</script>';
        }

        return '';
    }
}
