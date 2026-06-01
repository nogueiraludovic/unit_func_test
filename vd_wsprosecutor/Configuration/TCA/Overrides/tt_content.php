<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdWsprosecutor',
        'OfficeHourCurrent',
        'LLL:EXT:vd_wsprosecutor/Resources/Private/Language/locallang_db_new_content_el.xlf:plugins_vdwsprosecutor_officehourcurrent_title'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdWsprosecutor',
        'OfficeHourList',
        'LLL:EXT:vd_wsprosecutor/Resources/Private/Language/locallang_db_new_content_el.xlf:plugins_vdwsprosecutor_officehourlist_title'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdwsprosecutor_officehourcurrent'] =
        'layout,pages,recursive';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdwsprosecutor_officehourlist'] =
        'layout,pages,recursive';
})();
