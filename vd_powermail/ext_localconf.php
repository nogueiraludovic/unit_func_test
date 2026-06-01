<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_powermail/Configuration/TsConfig/Page/TceForm/*.tsconfig\'
    ');

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['fr']['EXT:powermail/Resources/Private/Language/locallang_db.xlf'][] =
        'EXT:vd_powermail/Resources/Private/Language/Overrides/fr.locallang_db.xlf';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\In2code\Powermail\Domain\Repository\FormRepository::class]['className'] =
        \Vd\VdPowermail\Domain\Repository\FormRepository::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\In2code\Powermail\Controller\FormController::class]['className'] =
        \Vd\VdPowermail\Controller\FormController::class;
})();
