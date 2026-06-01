<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'address' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'type' => 'text'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:address'
        ],
        'city' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:city'
        ],
        'contact' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:contact'
        ],
        'discount' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'FIELD:sale_categories:=:2',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:discount'
        ],
        'email' => [
            'config' => [
                'default' => '',
                'eval' => 'email,trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:email'
        ],
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'exposure' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => [
                'AND' => [
                    'FIELD:sale_categories:!=:2',
                    'REC:NEW:false'
                ]
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:exposure'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'main_office' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'eval' => 'required',
                'foreign_table' => 'tx_vdojvencheres_domain_model_office',
                'foreign_table_where' => 'AND (tx_vdojvencheres_domain_model_office.uid=###PAGE_TSCONFIG_ID###)',
                'minitems' => 1,
                'readOnly' => true,
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:main_office'
        ],
        'name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:name'
        ],
        'observations' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'eval' => 'trim',
                'type' => 'text'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:observations'
        ],
        'park_information' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'displayCond' => [
                'AND' => [
                    'FIELD:sale_categories:!=:1',
                    'REC:NEW:false'
                ]
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:park_information'
        ],
        'phone' => [
            'config' => [
                'default' => '',
                'eval' => \Vd\VdOjvencheres\TCA\Evaluation\PhoneEvaluation::class . ',trim,required',
                'max' => 255,
                'size' => 10,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:phone'
        ],
        'place' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'FIELD:sale_categories:!=:1',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale_place'
        ],
        'po_box' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 4,
                'size' => 10,
                'type' => 'input'
            ],
            'displayCond' => 'FIELD:sale_categories:=:1',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:po_box'
        ],
        'pub_date' => [
            'config' => [
                'default' => 0,
                'eval' => 'datetime,required',
                'renderType' => 'inputDateTime',
                'type' => 'input'
            ],
            'displayCond' => [
                'AND' => [
                    'FIELD:status:!IN:4,5',
                    'REC:NEW:false'
                ]
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:pub_date'
        ],
        'room' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => [
                'AND' => [
                    'FIELD:sale_categories:!=:1',
                    'REC:NEW:false'
                ]
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:room',
        ],
        'sale_categories' => [
            'config' => [
                'default' => '',
                'disableNoMatchingValueElement' => true,
                'eval' => 'required',
                'foreign_table' => 'tx_vdojvencheres_domain_model_salecategory',
                'items' => [
                    [
                        '',
                        ''
                    ]
                ],
                'minitems' => 1,
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'onChange' => 'reload',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale_categories'
        ],
        'sale_conditions' => [
            'config' => [
                'default' => '',
                'disableNoMatchingValueElement' => true,
                'eval' => 'required',
                'foreign_table' => 'tx_vdojvencheres_domain_model_salecondition',
                'items' => [
                    [
                        '',
                        ''
                    ]
                ],
                'minitems' => 1,
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'displayCond' => [
                'AND' => [
                    'FIELD:sale_categories:!=:0',
                    'REC:NEW:false'
                ]
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale_conditions'
        ],
        'sale_date' => [
            'config' => [
                'appearance' => [
                    'collapseAll' => true,
                    'newRecordLinkAddTitle' => true
                ],
                'default' => 0,
                'eval' => 'required',
                'foreign_field' => 'sale',
                'foreign_label' => 'sale_date',
                'foreign_table' => 'tx_vdojvencheres_domain_model_saledate',
                'maxitems' => 1,
                'minitems' => 1,
                'type' => 'inline'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale_date'
        ],
        'sale_items' => [
            'config' => [
                'default' => 0,
                'foreign_table' => 'tx_vdojvencheres_domain_model_item',
                'MM' => 'tx_vdojvencheres_item_sale_mm',
                'MM_opposite_field' => 'sale',
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'displayCond' => 'REC:NEW:false',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:items'
        ],
        'secondary_office' => [
            'config' => [
                'default' => 0,
                'foreign_table' => 'tx_vdojvencheres_domain_model_office',
                'foreign_table_where' => 'AND (tx_vdojvencheres_domain_model_office.uid!=###PAGE_TSCONFIG_ID###)',
                'MM' => 'tx_vdojvencheres_sale_officeecondary_office_mm',
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'displayCond' => [
                'AND' => [
                    'FIELD:sale_categories:!=:1',
                    'REC:NEW:false'
                ]
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:secondary_office'
        ],
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
        'status' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'items' => [
                    [
                        'En préparation',
                        0
                    ],
                    [
                        'Publiée',
                        1
                    ],
                    [
                        'Annulée',
                        2
                    ],
                    [
                        'Effectuée',
                        4
                    ],
                    [
                        'A supprimer',
                        5
                    ]
                ],
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'displayCond' => 'FIELD:status:!=:0',
            'onChange' => 'reload',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:status'
        ],
        'tstamp' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'zip_code' => [
            'config' => [
                'default' => '',
                'eval' => \Vd\VdOjvencheres\TCA\Evaluation\ZipEvaluation::class . ',trim,required',
                'max' => 4,
                'size' => 10,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:zip_code'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'endtime' => 'endtime',
            'starttime' => 'starttime'
        ],
        'label' => 'name',
        'label_alt' => 'status',
        'label_alt_force' => true,
        'searchFields' => 'address,city,contact,discount,exposure,name,observations,park_information,place,room,zip_code',
        'title' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sales',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-x-sale'
        ]
    ],
    'palettes' => [
        'access' => [
            'showitem' => 'starttime,endtime'
        ],
        'address' => [
            'showitem' => 'address,--linebreak--,po_box,zip_code,city'
        ],
        'contact' => [
            'showitem' => 'contact,phone,email'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    sale_categories,
                    name,
                    main_office,
                    secondary_office,
                    sale_date,
                    --palette--;;address,
                    room,
                    park_information,
                    discount,
                    sale_conditions,
                    exposure,
                    --palette--;;contact,
                    observations,
                --div--;LLL:EXT:vd_ojvencheres/Resources/Private/Language/Form/locallang_tabs.xlf:publication,
                    pub_date,
                    status,
                --div--;LLL:EXT:vd_ojvencheres/Resources/Private/Language/Form/locallang_tabs.xlf:items,
                    sale_items,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
            '
        ]
    ]
];
