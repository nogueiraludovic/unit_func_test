<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
        'tx_vdwsprosecutor_domain_model_officehour'
    );
})();
