<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdSmallAds',
        'Pi1',
        'VD Small Ads'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdsmallads_pi1'] =
        'layout,pages,recursive';
})();
