<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $tables = [
        'tx_vdcontactservice_domain_model_department',
        'tx_vdcontactservice_domain_model_service',
        'tx_vdcontactservice_domain_model_servicecontact'
    ];

    foreach ($tables as $table) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages($table);
    }
})();
