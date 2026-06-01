<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'vd_site_offres_emploi',
        'Configuration/TypoScript/',
        'VD Site Offres Emploi'
    );
})();
