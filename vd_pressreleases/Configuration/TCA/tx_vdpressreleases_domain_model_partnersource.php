<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'description' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:description'
        ],
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'logo' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'logo',
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
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:logo'
        ],
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
        'title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:name',
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
        'label' => 'title',
        'searchFields' => 'description,title',
        'title' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:partner_sources',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdpressreleases-partnersource'
        ]
    ],
    'palettes' => [
        'access' => [
            'showitem' => 'starttime,endtime'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    title,
                    description,
                    logo,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
                '
        ]
    ]
];
