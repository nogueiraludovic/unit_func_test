<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'account_number' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:account_number'
        ],
        'address' => [
            'config' => [
                'cols' => 20,
                'default' => '',
                'eval' => 'trim',
                'rows' => 3,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.address'
        ],
        'additional_text' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:additional_text'
        ],
        'additional_title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:additional_title'
        ],
        'email_address' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,email',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.email'
        ],
        'email_display_type' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'items' => [
                    [
                        'Aucun affichage',
                        0
                    ],
                    [
                        'Afficher l\'email',
                        1
                    ],
                    [
                        'Afficher le bouton « nous écrire » (formulaire de contact)',
                        2
                    ],
                    [
                        'Afficher l\'email et le bouton « nous écrire » (formulaire de contact)',
                        3
                    ]
                ],
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:email_display_type'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'image' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'useSortable' => false
                    ],
                    'default' => 0,
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
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.image'
        ],
        'internet_fax' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.fax'
        ],
        'link' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkFields' => 'class,params,target'
                        ]
                    ]
                ],
                'max' => 1024,
                'renderType' => 'inputLink',
                'size' => 50,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:link'
        ],
        'link_label' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:link_label'
        ],
        'locality' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:locality'
        ],
        'name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.name'
        ],
        'person_function' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:person_function'
        ],
        'person_name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:person_name'
        ],
        'postal_box' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:postal_box'
        ],
        'postal_code' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:postal_code'
        ],
        'service' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'eval' => 'required',
                'foreign_table' => 'tx_vdcontactservice_domain_model_service',
                'foreign_table_where' => 'ORDER BY tx_vdcontactservice_domain_model_service.name',
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
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:service'
        ],
        'summary' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:summary'
        ],
        'telephone' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.phone'
        ],
        'title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.title'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'name',
        'label_userFunc' => \Vd\VdContactService\TCA\Form\LabelRenderer::class . '->forServiceContact',
        'searchFields' => 'additional_text,additional_title,address,locality,person_function,person_name,postal_code,summary,title',
        'sortby' => 'sorting',
        'title' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:service_contact',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'palettes' => [
        'address' => [
            'showitem' => 'address,--linebreak--,postal_code,locality,--linebreak--,postal_box'
        ],
        'contactNumber' => [
            'showitem' => 'telephone,internet_fax'
        ],
        'email' => [
            'showitem' => 'email_address,email_display_type'
        ],
        'link' => [
            'showitem' => 'link,--linebreak--,link_label'
        ],
        'person' => [
            'showitem' => 'person_name,person_function'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    service,
                    name,
                    title,
                    summary,
                    --palette--;;person,
                    --palette--;;link,
                --div--;LLL:EXT:vd_contact_service/Resources/Private/Language/Form/locallang_tabs.xlf:address,
                    --palette--;;address,
                    --palette--;;email,
                    --palette--;;contactNumber,
                --div--;LLL:EXT:vd_contact_service/Resources/Private/Language/Form/locallang_tabs.xlf:medias,
                    image,
                --div--;LLL:EXT:vd_contact_service/Resources/Private/Language/Form/locallang_tabs.xlf:misc,
                    account_number,
                    additional_title,
                    additional_text,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
