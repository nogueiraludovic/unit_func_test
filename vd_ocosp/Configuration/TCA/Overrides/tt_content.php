<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $plugins = [
        'DisplayAdresses' => 'displayAdresses',
        'DisplayApprentissages' => 'displayApprentissages',
        'DisplayDmdeAdvancedSearch' => 'displayDmdeAdvancedSearch',
        'DisplayDmdeSimpleSearch' => 'displayDmdeSimpleSearch',
        'DisplayInscription' => 'displayInscription',
        'DisplayProfession' => 'displayProfession',
        'DisplaySalary' => 'displaySalary'
    ];

    foreach ($plugins as $pluginName => $pluginLabel) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'VdOcosp',
            $pluginName,
            'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_plugins.xlf:' . $pluginLabel,
            null,
            'OCOSP'
        );
    }

    $pluginsSignature = [
        'vdocosp_displayadresses',
        'vdocosp_displayapprentissages',
        'vdocosp_displaydmdeadvancedsearch',
        'vdocosp_displaydmdesimplesearch',
        'vdocosp_displayinscription',
        'vdocosp_displayprofession',
        'vdocosp_displaysalary'
    ];

    foreach ($pluginsSignature as $pluginSignature) {
        if (
            $pluginSignature === 'vdocosp_displaydmdeadvancedsearch'
            || $pluginSignature === 'vdocosp_displaydmdesimplesearch'
        ) {
            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
                $pluginSignature,
                'FILE:EXT:vd_ocosp/Configuration/FlexForms/Dmde.xml'
            );
        }

        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] =
            'layout,pages,recursive';
    }
})();
