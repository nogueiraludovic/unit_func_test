<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'vd_ojvencheres',
        'Configuration/TypoScript/',
        'VD OJV - Configuration'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'vd_ojvencheres',
        'Configuration/TypoScript/Newsletter/',
        'VD OJV - Newsletter'
    );
})();
