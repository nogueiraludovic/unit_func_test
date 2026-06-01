<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdOjvencheres',
        'ItemList',
        'Liste des objets',
        null,
        'OJV Enchères'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdOjvencheres',
        'ItemShow',
        'Détail d\'un objet',
        null,
        'OJV Enchères'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdOjvencheres',
        'Newsletter',
        'Newsletter',
        null,
        'OJV Enchères'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdOjvencheres',
        'SaleList',
        'Liste des ventes',
        null,
        'OJV Enchères'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdOjvencheres',
        'SaleShow',
        'Détail d\'une vente',
        null,
        'OJV Enchères'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'vdojvencheres_itemlist',
        'FILE:EXT:vd_ojvencheres/Configuration/FlexForms/List.xml'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'vdojvencheres_newsletter',
        'FILE:EXT:vd_ojvencheres/Configuration/FlexForms/Newsletter.xml'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'vdojvencheres_salelist',
        'FILE:EXT:vd_ojvencheres/Configuration/FlexForms/List.xml'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['vdojvencheres_itemlist'] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['vdojvencheres_newsletter'] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['vdojvencheres_salelist'] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdojvencheres_itemlist'] =
        'layout,pages,recursive';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdojvencheres_itemshow'] =
        'layout,pages,recursive';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdojvencheres_newsletter'] =
        'layout,pages,recursive';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdojvencheres_salelist'] =
        'layout,pages,recursive';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdojvencheres_saleshow'] =
        'layout,pages,recursive';
})();
