<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3_MODE') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'vd_lada',
        'Configuration/TypoScript/',
        'VD LADA'
    );
})();
