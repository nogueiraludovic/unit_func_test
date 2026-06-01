<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \Vd\VdFrontend\Utility\FlexFormUtility::registerTable(
        'tx_vdapprenticeship_apprenticeship',
        [
            'available_places',
            'dgav_language',
            'dgav_profession',
            'dgav_type',
            'region'
        ]
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
        \Vd\VdApprenticeship\Hooks\DataHandlerHook::class;
})();
