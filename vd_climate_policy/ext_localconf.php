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
            'address',
            'bodytext',
            'city',
            'email',
            'name',
            'sector',
            'service',
            'theme',
            'www',
            'zip'
        ],
        [
            'name'
        ]
    );

    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = '^tx_climatepolicy[';

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\Vd\VdFrontend\Database\RecordRepository::class]['className'] =
        \Vd\VdClimatePolicy\XClasses\Database\RecordRepositoryXClass::class;
})();
