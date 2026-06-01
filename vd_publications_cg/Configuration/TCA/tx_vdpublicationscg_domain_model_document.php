<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'crdate' => [
            'config' => [
                'default' => 0,
                'eval' => 'datetime',
                'readOnly' => true,
                'size' => 10,
                'renderType' => 'inputDateTime',
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_publications_cg/Resources/Private/Language/locallang.xlf:creation_date'
        ],
        'description' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('description'),
        'files' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'files',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'expandSingle' => true
                    ],
                    'default' => 0,
                    'minitems' => 1,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_APPLICATION => [
                                'showitem' => '
                                    title,
                                    --palette--;;filePalette
                                '
                            ]
                        ]
                    ]
                ],
                'docx,dotm,dotx,ili,pdf,txt,xlsm,xlsx,xltm,xltx,zip'
            ),
            'label' => 'LLL:EXT:vd_publications_cg/Resources/Private/Language/locallang.xlf:files'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'number' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'size' => 10,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_publications_cg/Resources/Private/Language/locallang_tca.xlf:number'
        ],
        'publication_date' => [
            'config' => [
                'default' => 0,
                'eval' => 'date',
                'size' => 10,
                'renderType' => 'inputDateTime',
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_publications_cg/Resources/Private/Language/locallang_tca.xlf:publication_date'
        ],
        'publisher' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdpublicationscg_domain_model_publisher',
                'items' => [
                    [
                        '',
                        ''
                    ]
                ],
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_publications_cg/Resources/Private/Language/locallang_tca.xlf:publisher'
        ],
        'slug' => [
            'config' => [
                'default' => '',
                'eval' => 'uniqueInSite',
                'fallbackCharacter' => '-',
                'generatorOptions' => [
                    'fieldSeparator' => '-',
                    'fields' => [
                        'number'
                    ],
                    'prefixParentPageSlug' => true,
                    'replacements' => [
                        '/' => '-'
                    ]
                ],
                'size' => 50,
                'type' => 'slug'
            ],
            'label' => 'LLL:EXT:vd_base_rem/Resources/Private/Language/locallang_tca.xlf:slug'
        ],
        'text' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_publications_cg/Resources/Private/Language/locallang_tca.xlf:text'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'number',
        'delete' => 'deleted',
        'descriptionColumn' => 'description',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'number',
        'searchFields' => 'description,number,text',
        'title' => 'LLL:EXT:vd_publications_cg/Resources/Private/Language/locallang_tca.xlf:document',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'palettes' => [
        'dates' => [
            'showitem' => 'publication_date,--linebreak--,crdate'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    number,
                    slug,
                    text,
                    publisher,
                --div--;LLL:EXT:vd_publications_cg/Resources/Private/Language/Form/locallang_tabs.xlf:files,
                    files,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    --palette--;;dates,
                    description
            '
        ]
    ]
];
