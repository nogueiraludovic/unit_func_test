<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('VdNews', 'CategoryList', 'VD News');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'vdnews_categorylist',
        'FILE:EXT:vd_news/Configuration/FlexForms/CategoryList.xml'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['vdnews_categorylist'] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdnews_categorylist'] = 'layout,pages,recursive';
})();
