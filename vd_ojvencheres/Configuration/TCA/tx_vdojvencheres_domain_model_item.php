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
        'brand' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:brand'
        ],
        'canceled' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:canceled'
        ],
        'charge_state' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'charge_state',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'useSortable' => false
                    ],
                    'maxitems' => 1,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_AUDIO => [
                                'showitem' => '
                                    --palette--;;audioOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_VIDEO => [
                                'showitem' => '
                                    --palette--;;videoOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_APPLICATION => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ]
                        ]
                    ]
                ],
                'pdf'
            ),
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:expense_statement'
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
        'color' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:color'
        ],
        'conditions' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'eval' => 'trim',
                'type' => 'text'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale_conditions'
        ],
        'description' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'eval' => 'trim,required',
                'type' => 'text'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:description'
        ],
        'district' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:district'
        ],
        'documents' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'documents',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'expandSingle' => true
                    ],
                    'maxitems' => 20,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_AUDIO => [
                                'showitem' => '
                                    --palette--;;audioOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_VIDEO => [
                                'showitem' => '
                                    --palette--;;videoOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_APPLICATION => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ]
                        ]
                    ]
                ],
                'pdf'
            ),
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:documents'
        ],
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'expertise_date' => [
            'config' => [
                'default' => '',
                'eval' => 'date',
                'max' => 255,
                'renderType' => 'inputDateTime',
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:expertise_date'
        ],
        'expertise_report' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'expertise_report',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'useSortable' => false
                    ],
                    'maxitems' => 1,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_AUDIO => [
                                'showitem' => '
                                    --palette--;;audioOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_VIDEO => [
                                'showitem' => '
                                    --palette--;;videoOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_APPLICATION => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ]
                        ]
                    ]
                ],
                'pdf'
            ),
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:expertise_report'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'item_categories' => [
            'config' => [
                'default' => '',
                'disableNoMatchingValueElement' => true,
                'eval' => 'required',
                'foreign_table' => 'tx_vdojvencheres_domain_model_itemcategory',
                'foreign_table_where' => ' AND tx_vdojvencheres_domain_model_itemcategory.parent<>0 AND tx_vdojvencheres_domain_model_itemcategory.selectable=1',
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
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:item_categories'
        ],
        'item_conditions' => [
            'config' => [
                'default' => '',
                'disableNoMatchingValueElement' => true,
                'eval' => 'required',
                'foreign_table' => 'tx_vdojvencheres_domain_model_itemcondition',
                'items' => [
                    [
                        '',
                        ''
                    ]
                ],
                'maxitems' => 1,
                'minitems' => 1,
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'displayCond' => 'REC:NEW:false',
            'onChange' => 'reload',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:item_conditions'
        ],
        'item_sub_categories' => [
            'config' => [
                'default' => '',
                'disableNoMatchingValueElement' => true,
                'eval' => 'required',
                'foreign_table' => 'tx_vdojvencheres_domain_model_itemcategory',
                'items' => [
                    [
                        '',
                        ''
                    ],
                ],
                'minitems' => 1,
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sub_category'
        ],
        'kilometer' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'size' => 10,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:kilometer'
        ],
        'model' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:model'
        ],
        'municipality' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:municipality'
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
        'parcel_number' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:parcel_number'
        ],
        'path_segment' => [
            'config' => [
                'default' => '',
                'eval' => 'uniqueInSite',
                'fallbackCharacter' => '-',
                'generatorOptions' => [
                    'fields' => [
                        'name'
                    ],
                    'replacements' => [
                        '/' => '-'
                    ],
                ],
                'size' => 50,
                'type' => 'slug'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:path_segment'
        ],
        'pictures' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'pictures',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'expandSingle' => true
                    ],
                    'maxitems' => 20,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_AUDIO => [
                                'showitem' => '
                                    --palette--;;audioOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_VIDEO => [
                                'showitem' => '
                                    --palette--;;videoOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_APPLICATION => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ]
                        ]
                    ]
                ],
                'jpg,jpeg,png'
            ),
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:pictures'
        ],
        'price' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'size' => 10,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:price'
        ],
        'rooms_number' => [
            'config' => [
                'default' => '',
                'eval' => 'double2',
                'max' => 255,
                'size' => 10,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:rooms_number'
        ],
        'sale' => [
            'config' => [
                'default' => '',
                'disableNoMatchingValueElement' => true,
                'eval' => 'required',
                'foreign_table' => 'tx_vdojvencheres_domain_model_sale',
                'foreign_table_where' => 'AND tx_vdojvencheres_domain_model_sale.status IN (0,1)',
                'items' => [
                    [
                        '',
                        ''
                    ],
                ],
                'minitems' => 1,
                'MM' => 'tx_vdojvencheres_item_sale_mm',
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale'
        ],
        'sale_conditions_file' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'sale_conditions_file',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'useSortable' => false
                    ],
                    'maxitems' => 1,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_AUDIO => [
                                'showitem' => '
                                    --palette--;;audioOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_VIDEO => [
                                'showitem' => '
                                    --palette--;;videoOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_APPLICATION => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ]
                        ]
                    ]
                ],
                'pdf'
            ),
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale_conditions_file'
        ],
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
        'surface' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'type' => 'text'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:surface'
        ],
        'tstamp' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'year' => [
            'config' => [
                'default' => 0,
                'eval' => \Vd\VdOjvencheres\TCA\Evaluation\YearEvaluation::class . ',int',
                'max' => 4,
                'size' => 10,
                'type' => 'input'
            ],
            'displayCond' => 'REC:NEW:false',
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:building_year'
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
        'enablecolumns' => [
            'disabled' => 'hidden',
            'endtime' => 'endtime',
            'starttime' => 'starttime'
        ],
        'delete' => 'deleted',
        'label' => 'name',
        'searchFields' => 'address,brand,city,color,description,district,model,municipality,name,observations,parcel_number,surface,zip_code',
        'thumbnail' => 'pictures',
        'title' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:items',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-x-item'
        ]
    ],
    'palettes' => [
        'access' => [
            'showitem' => 'starttime,endtime'
        ],
        'address' => [
            'showitem' => 'address,--linebreak--,zip_code,city,--linebreak--,municipality,district'
        ],
        'property' => [
            'showitem' => 'year,rooms_number,--linebreak--,parcel_number,--linebreak--,surface'
        ],
        'vehicle' => [
            'showitem' => 'brand,model,color,--linebreak--,expertise_date,kilometer'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    item_categories,
                    name,
                    path_segment,
                    sale,
                    item_sub_categories,
                    price,
                    --palette--;;address,
                    --palette--;;property,
                    --palette--;;vehicle,
                    description,
                    sale_conditions_file,
                    charge_state,
                    expertise_report,
                    documents,
                    pictures,
                    observations,
                    item_conditions,
                    conditions,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
            '
        ]
    ]
];
