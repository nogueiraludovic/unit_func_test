<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'pages',
        [
            'tx_vdmunicipalitiessearch_districts' => [
                'config' => [
                    'default' => 0,
                    'disableNoMatchingValueElement' => true,
                    'foreign_table' => 'tx_vdmunicipalities_districts',
                    'foreign_table_where' => 'ORDER BY tx_vdmunicipalities_districts.name',
                    'renderType' => 'selectMultipleSideBySide',
                    'type' => 'select'
                ],
                'displayCond' => 'FIELD:tx_vdmunicipalitiessearch_institution:>:0',
                'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:districts'
            ],
            'tx_vdmunicipalitiessearch_institution' => [
                'config' => [
                    'default' => 0,
                    'disableNoMatchingValueElement' => true,
                    'foreign_table' => 'tx_vdmunicipalitiessearch_institutions',
                    'foreign_table_where' => 'ORDER BY tx_vdmunicipalitiessearch_institutions.name',
                    'items' => [
                        [
                            '',
                            0
                        ]
                    ],
                    'renderType' => 'selectSingle',
                    'type' => 'select'
                ],
                'onChange' => 'reload',
                'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:institution'
            ],
            'tx_vdmunicipalitiessearch_municipalities' => [
                'config' => [
                    'default' => 0,
                    'disableNoMatchingValueElement' => true,
                    'foreign_table' => 'tx_vdmunicipalities_municipalities',
                    'foreign_table_where' => 'ORDER BY tx_vdmunicipalities_municipalities.name_lower',
                    'renderType' => 'selectMultipleSideBySide',
                    'type' => 'select'
                ],
                'displayCond' => 'FIELD:tx_vdmunicipalitiessearch_institution:>:0',
                'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:municipalities'
            ]
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        '
            --div--;LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:institution,
                tx_vdmunicipalitiessearch_institution,
                tx_vdmunicipalitiessearch_districts,
                tx_vdmunicipalitiessearch_municipalities
        ',
        '',
        'after:solr_boost'
    );
})();
