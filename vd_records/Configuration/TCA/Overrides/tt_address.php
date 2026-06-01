<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_address',
        [
            'full_address' => [
                'config' => [
                    'autocomplete' => true,
                    'default' => '',
                    'eval' => 'trim',
                    'readOnly' => true,
                    'size' => 50,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:full_address'
            ],
            'tx_vdaddrgeneral_case_postale' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'max' => 80,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:po_box'
            ],
            'tx_vdaddrgeneral_categoryid1' => [
                'config' => [
                    'default' => 0,
                    'disableNoMatchingValueElement' => true,
                    'foreign_table' => 'tt_address_group',
                    'foreign_table_where' => 'AND tt_address_group.pid IN(###CURRENT_PID###,###PAGE_TSCONFIG_ID###) ORDER BY tt_address_group.uid',
                    'maxitems' => 10,
                    'renderType' => 'selectMultipleSideBySide',
                    'type' => 'select'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:region'
            ],
            'tx_vdaddrgeneral_categoryid2' => [
                'config' => [
                    'default' => 0,
                    'disableNoMatchingValueElement' => true,
                    'foreign_table' => 'tt_address_group',
                    'foreign_table_where' => 'AND tt_address_group.pid IN(###CURRENT_PID###,###PAGE_TSCONFIG_ID###) ORDER BY tt_address_group.uid',
                    'maxitems' => 10,
                    'renderType' => 'selectMultipleSideBySide',
                    'type' => 'select'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:situation'
            ],
            'tx_vdaddrgeneral_categoryid3' => [
                'config' => [
                    'default' => 0,
                    'disableNoMatchingValueElement' => true,
                    'foreign_table' => 'tt_address_group',
                    'foreign_table_where' => 'AND tt_address_group.pid IN(###CURRENT_PID###,###PAGE_TSCONFIG_ID###) ORDER BY tt_address_group.uid',
                    'maxitems' => 10,
                    'renderType' => 'selectMultipleSideBySide',
                    'type' => 'select'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:customers'
            ],
            'tx_vdaddrgeneral_categoryid4' => [
                'config' => [
                    'default' => 0,
                    'disableNoMatchingValueElement' => true,
                    'foreign_table' => 'tt_address_group',
                    'foreign_table_where' => 'AND tt_address_group.pid IN(###CURRENT_PID###,###PAGE_TSCONFIG_ID###) ORDER BY tt_address_group.uid',
                    'maxitems' => 10,
                    'renderType' => 'selectMultipleSideBySide',
                    'type' => 'select'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:degree'
            ],
            'tx_vdaddrgeneral_categoryid5' => [
                'config' => [
                    'default' => 0,
                    'disableNoMatchingValueElement' => true,
                    'foreign_table' => 'tt_address_group',
                    'foreign_table_where' => 'AND tt_address_group.pid IN(###CURRENT_PID###,###PAGE_TSCONFIG_ID###) ORDER BY tt_address_group.uid',
                    'maxitems' => 10,
                    'renderType' => 'selectMultipleSideBySide',
                    'type' => 'select'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:offer'
            ],
            'tx_vdaddrgeneral_categoryid6' => [
                'config' => [
                    'default' => 0,
                    'disableNoMatchingValueElement' => true,
                    'foreign_table' => 'tt_address_group',
                    'foreign_table_where' => 'AND tt_address_group.pid IN(###CURRENT_PID###,###PAGE_TSCONFIG_ID###) ORDER BY tt_address_group.uid',
                    'maxitems' => 10,
                    'renderType' => 'selectMultipleSideBySide',
                    'type' => 'select'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:categoryid6'
            ],
            'tx_vdlisteprofessionsdb_schoolacronym' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'max' => 80,
                    'size' => 10,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:school_acronym'
            ],
            'tx_vdlisteprofessionsdb_schoolname' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'max' => 255,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:school_name'
            ],
            'tx_vdttaddressextprofilmonichien_profil' => [
                'config' => [
                    'allowed' => 'tx_vdttaddressextprofilmonichien_profil',
                    'default' => 0,
                    'foreign_table' => 'tx_vdttaddressextprofilmonichien_profil',
                    'internal_type' => 'db',
                    'maxitems' => 5,
                    'type' => 'group'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:dog_trainers_profile'
            ]
        ]
    );

    $GLOBALS['TCA']['tt_address']['columns']['www']['config']['max'] = 1024;
    $GLOBALS['TCA']['tt_address']['columns']['www']['config']['size'] = 50;

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_address', 'address', 'full_address');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tt_address',
        '
            --div--;LLL:EXT:vd_records/Resources/Private/Language/Form/locallang_tabs.xlf:other,
                tx_vdttaddressextprofilmonichien_profil,
                tx_vdaddrgeneral_case_postale,
            --div--;LLL:EXT:vd_records/Resources/Private/Language/Form/locallang_tabs.xlf:professions,
                tx_vdlisteprofessionsdb_schoolacronym,
                tx_vdlisteprofessionsdb_schoolname,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                tx_vdaddrgeneral_categoryid1,
                tx_vdaddrgeneral_categoryid2,
                tx_vdaddrgeneral_categoryid3,
                tx_vdaddrgeneral_categoryid4,
                tx_vdaddrgeneral_categoryid5,
                tx_vdaddrgeneral_categoryid6
        ',
        '',
        'after:linkedin'
    );
})();
