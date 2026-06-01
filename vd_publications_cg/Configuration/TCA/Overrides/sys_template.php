<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'vd_publications_cg',
        'Configuration/TypoScript/',
        'VD Publications CG'
    );
})();
