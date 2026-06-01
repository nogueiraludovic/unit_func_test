<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'vd_contact_service',
        'Configuration/TypoScript/',
        'VD Service Contact'
    );
})();
