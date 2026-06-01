<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Mvc\Controller;

use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

use function max;

abstract class AbstractController extends ActionController
{
    protected AssetCollector $assetCollector;

    public function injectAssetCollector(AssetCollector $assetCollector): void
    {
        $this->assetCollector = $assetCollector;
    }

    protected function getPage(): int
    {
        if ($this->request->hasArgument('page') === true) {
            return max((int)$this->request->getArgument('page'), 1);
        }

        return 1;
    }

    protected function initializeAction(): void
    {
        $this->assetCollector->addJavaScript(
            'jwpsrv',
            'https://jwpsrv.com/library/NE86BrjQEeOiHyIACrqE1A.js',
            [],
            [
                'priority' => true
            ]
        );
        $this->assetCollector->addJavaScript(
            'bootstrap-autocomplete',
            'EXT:vd_ocosp/Resources/Public/JavaScript/Contribs/bootstrap-autocomplete.min.js'
        );
        $this->assetCollector->addJavaScript(
            'vd-ocosp',
            'EXT:vd_ocosp/Resources/Public/JavaScript/OcospDesktop.js'
        );
        $this->assetCollector->addJavaScript(
            'replace-diacritics',
            'EXT:vd_ocosp/Resources/Public/JavaScript/Contribs/ReplaceDiacritics/replace-diacritics.js'
        );
        // @extensionScannerIgnoreLine
        $this->assetCollector->addStyleSheet('vd-ocosp', 'EXT:vd_ocosp/Resources/Public/Css/ocosp.css');
    }
}
