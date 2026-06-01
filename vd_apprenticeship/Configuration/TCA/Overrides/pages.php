<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'vd_apprenticeship',
        'Configuration/TsConfig/Page/DGAV.tsconfig',
        'VD Apprenticeship - DGAV Page Configuration'
    );
})();
