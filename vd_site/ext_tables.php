<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $tables = [
        'tx_vdsite_domain_model_card',
        'tx_vdsite_domain_model_carouselitem',
        'tx_vdsite_domain_model_link',
        'tx_vdsite_queueitem'
    ];

    foreach ($tables as $table) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages($table);
    }
})();
