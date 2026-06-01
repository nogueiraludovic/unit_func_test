<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
        'vd_site_offres_emploi',
        'Configuration/TsConfig/Page/overrides.tsconfig',
        'VD Site Offres Emploi'
    );
})();
