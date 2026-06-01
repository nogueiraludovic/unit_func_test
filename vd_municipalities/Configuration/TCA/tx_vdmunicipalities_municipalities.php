<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'id_district' => [
            'config' => [
                'default' => 0,
                'foreign_table' => 'tx_vdmunicipalities_districts',
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:id_district'
        ],
        'id_municipality' => [
            'config' => [
                'default' => 0,
                'eval' => 'int',
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:id_municipality'
        ],
        'id_state' => [
            'config' => [
                'default' => 0,
                'eval' => 'int',
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:id_state'
        ],
        'idex2000' => [
            'config' => [
                'default' => 0,
                'eval' => 'int',
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:idex2000'
        ],
        'localites' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:localities'
        ],
        'name_lower' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:name_lower'
        ],
        'name_lower15' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:name_lower15'
        ],
        'name_upper' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,upper',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:name_upper'
        ],
        'name_upper15' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,upper',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:name_upper15'
        ],
        'npa' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:npa'
        ],
        'objectid' => [
            'config' => [
                'default' => 0,
                'eval' => 'int',
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:id_object'
        ],
        'surface' => [
            'config' => [
                'default' => 0,
                'eval' => 'int',
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:surface'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'name_lower',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'name_lower',
        'label_alt' => 'id_municipality',
        'label_alt_force' => true,
        'rootLevel' => true,
        'searchFields' => 'name_lower,name_lower15,name_upper,name_upper15',
        'title' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:municipalities',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'palettes' => [
        'address' => [
            'showitem' => 'npa,localites'
        ],
        'id' => [
            'showitem' => 'id_state,id_district,id_municipality,objectid'
        ],
        'name' => [
            'showitem' => 'name_lower,name_lower15,--linebreak--,name_upper,name_upper15'
        ],
        'surface' => [
            'showitem' => 'surface,idex2000'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;name,
                    --palette--;;id,
                    --palette--;;surface,
                    --palette--;;address,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
