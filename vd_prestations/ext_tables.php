<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $tables = [
        'tx_vdprestations_domain_model_accessmodality',
        'tx_vdprestations_domain_model_prestation',
        'tx_vdprestations_domain_model_targetaudience',
        'tx_vdprestations_domain_model_url'
    ];

    foreach ($tables as $table) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages($table);
    }
})();
