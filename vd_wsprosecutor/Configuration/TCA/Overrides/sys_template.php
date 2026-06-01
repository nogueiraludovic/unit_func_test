<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'vd_wsprosecutor',
        'Configuration/TypoScript/',
        'VD Web Service Prosecutor'
    );
})();
