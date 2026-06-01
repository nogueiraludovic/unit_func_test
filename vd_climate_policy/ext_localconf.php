<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_climate_policy/Configuration/TsConfig/Page/Mod/web_list.tsconfig\'
    ');

    \Vd\VdFrontend\Utility\FlexFormUtility::registerTable(
        'tx_vdclimatepolicy_domain_model_address',
        [
            'axis',
            'cities',
            'dicastery',
            'name',
            'pecc',
            'theme'
        ],
        [
            'name'
        ]
    );

    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = '^tx_climatepolicy[';

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\Vd\VdFrontend\Database\RecordRepository::class]['className'] =
        \Vd\VdClimatePolicy\XClasses\Database\RecordRepositoryXClass::class;

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][\Vd\VdClimatePolicy\Updates\ClimatePolicyAddDefaultEntriesWizard::class]
        = \Vd\VdClimatePolicy\Updates\ClimatePolicyAddDefaultEntriesWizard::class;
})();
