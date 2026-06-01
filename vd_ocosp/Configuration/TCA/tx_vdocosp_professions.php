<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'anciennes_denominations' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:former_names'
        ],
        'dmde' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdocosp_dmde',
                'foreign_table_where' => 'ORDER BY tx_vdocosp_dmde.profession',
                'MM' => 'tx_vdocosp_dmde_professions_mm',
                'MM_opposite_field' => 'profession_id',
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:careers_schools'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'inscriptions' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdocosp_inscriptions',
                'foreign_table_where' => 'ORDER BY tx_vdocosp_inscriptions.formation',
                'MM' => 'tx_vdocosp_inscriptions_profession_mm',
                'MM_opposite_field' => 'profession',
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:subscriptions'
        ],
        'mots_cles' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.keywords'
        ],
        'nom_fem' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:female_name'
        ],
        'nom_masc' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:male_name'
        ],
        'niveau_cnc' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:cnc_level'
        ],
        'remarques' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:remarks'
        ],
        'remarques_ext' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:external_remarks'
        ],
        'titre_delivre' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdocosp_diplomes',
                'foreign_table_where' => 'ORDER BY tx_vdocosp_diplomes.diplome',
                'minitems' => 1,
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:delivered_title'
        ],
        'tstamp' => [
            'config' => [
                'default' => 0,
                'type' => 'passthrough'
            ]
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'nom_masc',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'nom_masc',
        'searchFields' => 'anciennes_denominations,mots_cles,niveau_cnc,nom_fem,nom_masc,remarques,remarques_ext',
        'title' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:professions',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdocosp-profession'
        ]
    ],
    'palettes' => [
        'name' => [
            'showitem' => 'nom_masc,nom_fem'
        ],
        'remark' => [
            'showitem' => 'remarques,remarques_ext'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;name,
                    titre_delivre,
                    niveau_cnc,
                    anciennes_denominations,
                    mots_cles,
                    --palette--;;remark,
                    inscriptions,
                    dmde,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
