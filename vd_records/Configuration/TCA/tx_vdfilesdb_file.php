<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'categoryid' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdfilesdb_category',
                'foreign_table_where' => 'AND tx_vdfilesdb_category.pid IN(###CURRENT_PID###,###PAGE_TSCONFIG_ID###) ORDER BY tx_vdfilesdb_category.uid',
                'maxitems' => 10,
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:categoryid'
        ],
        'categoryid2' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdfilesdb_category',
                'foreign_table_where' => 'AND tx_vdfilesdb_category.pid IN(###CURRENT_PID###,###PAGE_TSCONFIG_ID###) ORDER BY tx_vdfilesdb_category.uid',
                'maxitems' => 10,
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:categoryid2'
        ],
        'categoryid3' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdfilesdb_category',
                'foreign_table_where' => 'AND tx_vdfilesdb_category.pid IN(###CURRENT_PID###,###PAGE_TSCONFIG_ID###) ORDER BY tx_vdfilesdb_category.uid',
                'maxitems' => 10,
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:categoryid3'
        ],
        'categoryid4' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdfilesdb_category',
                'foreign_table_where' => 'AND tx_vdfilesdb_category.pid IN(###CURRENT_PID###,###PAGE_TSCONFIG_ID###) ORDER BY tx_vdfilesdb_category.uid',
                'maxitems' => 10,
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:categoryid4'
        ],
        'categoryid5' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdfilesdb_category',
                'foreign_table_where' => 'AND tx_vdfilesdb_category.pid IN(###CURRENT_PID###,###PAGE_TSCONFIG_ID###) ORDER BY tx_vdfilesdb_category.uid',
                'maxitems' => 10,
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:categoryid5'
        ],
        'categoryid6' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdfilesdb_category',
                'foreign_table_where' => 'AND tx_vdfilesdb_category.pid IN(###CURRENT_PID###,###PAGE_TSCONFIG_ID###) ORDER BY tx_vdfilesdb_category.uid',
                'maxitems' => 10,
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:categoryid6'
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
        'filepathbyref1' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'filepathbyref1',
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
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:filepathbyref1'
        ],
        'filepathbyref2' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'filepathbyref2',
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
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:filepathbyref2'
        ],
        'filepathbyref3' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'filepathbyref3',
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
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:filepathbyref3'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'keywords' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:keywords'
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
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:link'
        ],
        'no' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:no'
        ],
        'sorting' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.title'
        ],
        'vdmunicipalitydistrictid' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdmunicipalities_districts',
                'foreign_table_where' => 'AND tx_vdmunicipalities_districts.pid=0 ORDER BY tx_vdmunicipalities_districts.name',
                'maxitems' => 10,
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:district'
        ],
        'vdmunicipalityid' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdmunicipalities_municipalities',
                'foreign_table_where' => 'AND tx_vdmunicipalities_municipalities.pid=0 ORDER BY tx_vdmunicipalities_municipalities.name_lower',
                'maxitems' => 10,
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:municipality'
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
        'searchFields' => 'description,keywords,link,ref,title',
        'sortby' => 'sorting',
        'title' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:files',
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
                    description,
                    keywords,
                    no,
                    link,
                    filepath,
                    filepathbyref1,
                    filepathbyref2,
                    filepathbyref3,
                    vdmunicipalitydistrictid,
                    vdmunicipalityid,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categoryid,
                    categoryid2,
                    categoryid3,
                    categoryid4,
                    categoryid5,
                    categoryid6,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
