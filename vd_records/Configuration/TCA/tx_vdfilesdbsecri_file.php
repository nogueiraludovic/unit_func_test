<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'addressee' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:recipient',
        ],
        'categoryid' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdfilesdbsecri_category',
                'foreign_table_where' => 'AND tx_vdfilesdbsecri_category.pid=###CURRENT_PID### ORDER BY tx_vdfilesdbsecri_category.uid',
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:categoryid'
        ],
        'datedocument' => [
            'config' => [
                'default' => 0,
                'eval' => 'date',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:date_document'
        ],
        'description' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.description'
        ],
        'filepath' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'filepath',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'expandSingle' => true
                    ],
                    'default' => 0,
                    'maxitems' => 10,
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
                'csv,doc,docx,gif,jpg,jpeg,odt,pdf,png,ppt,pptx,xls,xlsx'
            ),
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:filepath'
        ],
        'title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
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
        'label' => 'title',
        'searchFields' => 'addressee,description,title',
        'sortby' => 'sorting',
        'title' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:secri_files',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    title,
                    datedocument,
                    addressee,
                    description,
                    categoryid,
                    filepath,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
