<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $GLOBALS['TCA']['pages']['columns']['slug']['config']['generatorOptions']['fields'] = [
        [
            'nav_title',
            'title'
        ]
    ];

    $GLOBALS['TCA']['pages']['columns']['url']['config']['size'] = 50;

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'pages',
        [
            'google_tags_id' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'max' => 255,
                    'size' => 10,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:google_tags_id'
            ],
            'vd_hide_menu_button' => [
                'config' => [
                    'default' => false,
                    'renderType' => 'checkboxToggle',
                    'type' => 'check'
                ],
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:vd_hide_menu_button'
            ],
            'vd_nav_legend' => [
                'config' => [
                    'default' => '',
                    'type' => 'text'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:vd_nav_legend'
            ],
            'vd_show_menu' => [
                'config' => [
                    'default' => false,
                    'renderType' => 'checkboxToggle',
                    'type' => 'check'
                ],
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:vd_show_menu'
            ]
        ]
    );

    $GLOBALS['TCA']['pages']['palettes']['navigation']['showitem'] = 'vd_show_menu,vd_hide_menu_button';

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        'google_tags_id',
        '',
        'after:canonical_link'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        'vd_nav_legend',
        '',
        'after:subtitle'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        '--palette--;LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:palettes.navigation;navigation',
        '',
        'before:cache_timeout'
    );
})();
