<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'cat' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.category'
        ],
        'cat2' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_small_ads/Resources/Private/Language/locallang_be.xlf:category_2'
        ],
        'comment' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_small_ads/Resources/Private/Language/locallang_be.xlf:comment'
        ],
        'content' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.text'
        ],
        'crdateexternal' => [
            'config' => [
                'default' => 0,
                'readOnly' => true,
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_small_ads/Resources/Private/Language/locallang_be.xlf:crdateexternal'
        ],
        'description' => [
            'config' => [
                'cols' => 30,
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.description'
        ],
        'displayemail' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_small_ads/Resources/Private/Language/locallang_be.xlf:displayemail'
        ],
        'email' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,email',
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.email'
        ],
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'image' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'enabledControls' => [
                            'dragdrop' => false
                        ]
                    ],
                    'default' => 0,
                    'maxitems' => 1,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_UNKNOWN => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                                'showitem' => '
                                    --palette--;;audioOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                                'showitem' => '
                                    --palette--;;videoOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
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
        'iscommercial' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_small_ads/Resources/Private/Language/locallang_be.xlf:iscommercial'
        ],
        'phone' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.phone'
        ],
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
        'reviewed' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_small_ads/Resources/Private/Language/locallang_be.xlf:reviewed'
        ],
        'slug' => [
            'config' => [
                'default' => '',
                'eval' => 'uniqueInSite',
                'fallbackCharacter' => '-',
                'generatorOptions' => [
                    'fields' => [
                        'title'
                    ],
                    'fieldSeparator' => '-',
                    'replacements' => [
                        '/' => '-'
                    ]
                ],
                'size' => 50,
                'type' => 'slug'
            ],
            'displayCond' => 'VERSION:IS:false',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug',
        ],
        'title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.title'
        ],
        'user' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_small_ads/Resources/Private/Language/locallang_be.xlf:user'
        ],
        'vdexternaluid' => [
            'config' => [
                'default' => 0,
                'readOnly' => true,
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_small_ads/Resources/Private/Language/locallang_be.xlf:vdexternaluid'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'crdate DESC',
        'delete' => 'deleted',
        'descriptionColumn' => 'description',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'endtime' => 'endtime',
            'starttime' => 'starttime'
        ],
        'label' => 'title',
        'searchFields' => 'cat,cat1,cat2,comment,content,description,email,phone,title,user',
        'title' => 'LLL:EXT:vd_small_ads/Resources/Private/Language/locallang_be.xlf:small_ads',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-small-ad'
        ]
    ],
    'palettes' => [
        'access' => [
            'showitem' => 'starttime,endtime'
        ],
        'email' => [
            'showitem' => 'displayemail,--linebreak--,email'
        ],
        'external' => [
            'showitem' => 'crdateexternal,vdexternaluid'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    iscommercial,
                    title,
                    slug,
                    content,
                --div--;LLL:EXT:vd_small_ads/Resources/Private/Language/Form/locallang_tabs.xlf:advertiser,
                    user,
                    --palette--;;email,
                    phone,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.media,
                    image,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    reviewed,
                    hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    cat,
                    cat2,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    comment,
                    description,
                    --palette--;;external
            '
        ]
    ]
];
