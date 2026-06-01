<?php

namespace Vd\VdSqliCalculetteAci\Plugin;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;
use Vd\VdSqliCalculetteAci\Controller\Affichage;

class AciCalculatorPlugin extends AbstractPlugin
{
    public $prefixId = 'tx_vdsqlicalculetteaci_pi1';
    public $scriptRelPath = 'Classes/Plugin/AciCalculatorPlugin.php';
    public $extKey = 'vd_sqli_calculetteaci';

    public function main(string $content, array $conf): string
    {
        $this->conf = $conf;
        $this->pi_setPiVarDefaults();
        $this->pi_loadLL();
        // @extensionScannerIgnoreLine
        $this->pi_USER_INT_obj = true;

        $view = GeneralUtility::makeInstance(Affichage::class);
        // @extensionScannerIgnoreLine
        $view->init($this);

        return $this->pi_wrapInBaseClass($view->getContent(false, $this->conf['detailCalcul']));
    }
}
