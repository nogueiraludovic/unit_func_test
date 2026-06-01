<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $GLOBALS['TCA']['sys_file_reference']['columns']['alternative']['config']['eval'] = 'trim,required';

    unset(
        $GLOBALS['TCA']['sys_file_reference']['columns']['alternative']['config']['mode'],
        $GLOBALS['TCA']['sys_file_reference']['columns']['alternative']['config']['placeholder']
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'sys_file_reference',
        [
            'owner' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'size' => 20,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:owner'
            ]
        ]
    );

    $GLOBALS['TCA']['sys_file_reference']['columns']['showinpreview']['config']['default'] = 1;
    $GLOBALS['TCA']['sys_file_reference']['columns']['showinpreview']['config']['readOnly'] = true;

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        'sys_file_reference',
        'imageoverlayPalette',
        'owner'
    );

    $GLOBALS['TCA']['sys_file_reference']['palettes']['minimalPalette']['showitem'] =
        'title,alternative,--linebreak--,crop';
})();
