<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdPrestations',
        'Pi1',
        'VD Prestations - Liste courte',
        null,
        'Prestation'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdPrestations',
        'Pi2',
        'VD Prestations - Liste complète',
        null,
        'Prestation'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdPrestations',
        'Pi3',
        'VD Prestations - Barre de recherche',
        null,
        'Prestation'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'VdPrestations',
        'Pi4',
        'VD Prestations - Vue de détail',
        null,
        'Prestation'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'vdprestations_pi1',
        'FILE:EXT:vd_prestations/Configuration/FlexForms/Pi1.xml'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'vdprestations_pi2',
        'FILE:EXT:vd_prestations/Configuration/FlexForms/Pi2.xml'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['vdprestations_pi1'] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['vdprestations_pi2'] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdprestations_pi1'] =
        'layout,pages,recursive';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdprestations_pi2'] =
        'layout,pages,recursive';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdprestations_pi3'] =
        'layout,pages,recursive';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['vdprestations_pi4'] =
        'layout,pages,recursive';
})();
