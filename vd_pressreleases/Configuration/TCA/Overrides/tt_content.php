<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdPressreleases',
        'PressRelease',
        'VD Press Releases'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdpressreleases_pressrelease'] =
        'layout,pages,recursive';
})();
