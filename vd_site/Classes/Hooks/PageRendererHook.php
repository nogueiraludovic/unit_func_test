<?php

declare(strict_types=1);

namespace Vd\VdSite\Hooks;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;

class PageRendererHook
{
    /** @noinspection PhpUnusedParameterInspection */
    public function addCssFile(array $_, PageRenderer $pageRenderer): void
    {
        if (GeneralUtility::makeInstance(EnvironmentService::class)->isEnvironmentInBackendMode() === false) {
            return;
        }

        $pageRenderer->addCssFile('EXT:vd_site/Resources/Public/Css/Backend/page-tree.css');
    }
}
