<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdContactService',
        'Contact',
        'VD Service Contact - Show',
        null,
        'Service Contact'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdContactService',
        'Search',
        'VD Service Contact - Search',
        null,
        'Service Contact'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'vdcontactservice_contact',
        'FILE:EXT:vd_contact_service/Configuration/FlexForms/Contact.xml'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'vdcontactservice_search',
        'FILE:EXT:vd_contact_service/Configuration/FlexForms/Search.xml'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['vdcontactservice_contact'] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['vdcontactservice_search'] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdcontactservice_contact'] =
        'layout,pages,recursive';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdcontactservice_search'] =
        'layout,pages,recursive';
})();
