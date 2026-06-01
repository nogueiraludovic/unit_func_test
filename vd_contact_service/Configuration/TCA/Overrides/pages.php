<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'pages',
        [
            'service_contact' => [
                'config' => [
                    'default' => 0,
                    'renderType' => 'serviceContact',
                    'type' => 'user'
                ],
                'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:service_contact'
            ],
            'service_contact_hidden' => [
                'config' => [
                    'default' => false,
                    'renderType' => 'checkboxToggle',
                    'type' => 'check'
                ],
                'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:service_contact_hidden'
            ],
            'service_contact_hidden_subpages' => [
                'config' => [
                    'default' => false,
                    'renderType' => 'checkboxToggle',
                    'type' => 'check'
                ],
                'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:service_contact_hidden_subpages'
            ]
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        '
            --div--;Service,
                service_contact,
                service_contact_hidden,
                service_contact_hidden_subpages
        '
    );
})();
