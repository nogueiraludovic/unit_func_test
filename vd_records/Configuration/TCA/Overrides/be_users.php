<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'be_users',
        [
            'tx_vdcontributors_atevid' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'max' => 80,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:atev_id'
            ],
            'tx_vdcontributors_service' => [
                'config' => [
                    'default' => 0,
                    'disableNoMatchingValueElement' => true,
                    'foreign_table' => 'tx_vddptservices_services',
                    'foreign_table_where' => 'ORDER BY tx_vddptservices_services.department_id,tx_vddptservices_services.code',
                    'items' => [
                        [
                            '',
                            0
                        ]
                    ],
                    'renderType' => 'selectSingle',
                    'type' => 'select'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:vd_service'
            ],
            'tx_vdcontributors_supercontrib' => [
                'config' => [
                    'default' => false,
                    'renderType' => 'checkboxToggle',
                    'type' => 'check'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:super_contributor'
            ],
            'tx_vdcontributors_vd_userid' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'max' => 25,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:vd_user_id'
            ],
            'tx_vdcontributors_web_adviser' => [
                'config' => [
                    'default' => 0,
                    'disableNoMatchingValueElement' => true,
                    'foreign_table' => 'be_users',
                    'foreign_table_where' => 'ORDER BY be_users.username',
                    'items' => [
                        [
                            '',
                            0
                        ]
                    ],
                    'renderType' => 'selectSingle',
                    'type' => 'select'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:web_adviser'
            ]
        ]
    );

    // @TODO: not used: to be deleted?
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'be_users',
        '
            tx_vdcontributors_service,
            tx_vdcontributors_web_adviser,
            tx_vdcontributors_vd_userid,
            tx_vdcontributors_supercontrib,
            tx_vdcontributors_atevid
        '
    );
})();
