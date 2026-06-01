<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3_MODE') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_lada/Configuration/TsConfig/Page/Mod/web_list.tsconfig\'
    ');

    \Vd\VdFrontend\Utility\FlexFormUtility::registerTable(
        'tx_vdlada_domain_model_housing',
        [
            'city',
            'health_network',
            'housing_type',
            'near_ems'
        ]
    );
})();
