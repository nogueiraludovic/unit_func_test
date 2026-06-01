<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'adresse' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.address'
        ],
        'adresse_2' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:adresse_next'
        ],
        'code_postal' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'size' => 5,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.zip'
        ],
        'delais' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:deadline'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'nom' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:name'
        ],
        'site_web' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkFields' => 'class,params,target',
                            'blindLinkOptions' => 'file,folder,mail,news,news_category,page,tx_vdcontactservice_domain_model_service,tx_vdpressreleases_pressrelease,tx_vdprestations'
                        ]
                    ]
                ],
                'max' => 1024,
                'renderType' => 'inputLink',
                'size' => 50,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.www'
        ],
        'tstamp' => [
            'config' => [
                'default' => 0,
                'type' => 'passthrough'
            ]
        ],
        'type_adresse' => [
            'config' => [
                'default' => 'privee',
                'disableNoMatchingValueElement' => true,
                'items' => [
                    [
                        'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:address_type.I.private_school',
                        'privee'
                    ],
                    [
                        'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:address_type.I.secondary_1_school',
                        'second_1'
                    ],
                    [
                        'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:address_type.I.secondary_2_school',
                        'second_2'
                    ],
                    [
                        'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:address_type.I.school_tertiary_level',
                        'tertiaire'
                    ],
                    [
                        'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:address_type.I.association',
                        'assoc'
                    ],
                    [
                        'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:address_type.I.company',
                        'entreprise'
                    ],
                    [
                        'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:address_type.I.other',
                        'autre'
                    ]
                ],
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:address_type'
        ],
        'ville' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.city'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'nom',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'nom',
        'searchFields' => 'adresse,adresse_2,code_postal,nom,site_web,ville',
        'title' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:addresses',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdocosp-adresse'
        ]
    ],
    'palettes' => [
        'address' => [
            'showitem' => 'type_adresse,--linebreak--,adresse,adresse_2,--linebreak--,code_postal,ville'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    nom,
                    --palette--;;address,
                    site_web,
                    delais,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
