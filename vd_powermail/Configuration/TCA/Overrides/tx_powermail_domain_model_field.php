<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tx_powermail_domain_model_field',
        [
            'error_text' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'max' => 255,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_powermail/Resources/Private/Language/locallang_be.xlf:error_text'
            ],
            'help_text' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'max' => 255,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_powermail/Resources/Private/Language/locallang_be.xlf:help_text'
            ]
        ]
    );

    $GLOBALS['TCA']['tx_powermail_domain_model_field']['columns']['text']['config'] = [
        'default' => '',
        'enableRichtext' => true,
        'type' => 'text'
    ];

    $GLOBALS['TCA']['tx_powermail_domain_model_field']['palettes']['texts']['showitem'] = 'error_text,help_text';

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tx_powermail_domain_model_field',
        '--palette--;;texts',
        'check,date,input,radio,select,textarea',
        'after:type'
    );
})();
