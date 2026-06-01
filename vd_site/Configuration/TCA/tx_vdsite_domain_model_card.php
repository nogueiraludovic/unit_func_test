<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'icon' => [
            'config' => [
                'default' => '',
                'renderType' => 'iconPicker',
                'type' => 'user'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:icon'
        ],
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
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;minimalPalette,
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
        'link' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
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
            'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:link'
        ],
        'rowDescription' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('description'),
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
        'text' => [
            'config' => [
                'cols' => 80,
                'default' => '',
                'eval' => 'trim',
                'rows' => 5,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.text'
        ],
        'title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'size' => 50,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.title'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'descriptionColumn' => 'rowDescription',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'endtime' => 'endtime',
            'starttime' => 'starttime'
        ],
        'hideTable' => true,
        'label' => 'title',
        'searchFields' => 'rowDescription,text,title',
        'sortby' => 'sorting',
        'title' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:cards',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'content-card'
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
                    text,
                    link,
                --div--;LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:tabs.icon,
                    image,
                    icon,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription
            '
        ]
    ]
];
