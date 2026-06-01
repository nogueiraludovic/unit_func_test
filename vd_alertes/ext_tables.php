<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
        'tx_vdalertes_domain_model_home_alert'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
        'tx_vdalertes_domain_model_service_alert'
    );
})();
