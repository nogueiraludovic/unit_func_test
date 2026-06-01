<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'depth' => [
            'config' => [
                'default' => 1,
                'disableNoMatchingValueElement' => true,
                'items' => [
                    [
                        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:recursive.I.0',
                        0
                    ],
                    [
                        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:recursive.I.1',
                        1
                    ],
                    [
                        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:recursive.I.2',
                        2
                    ],
                    [
                        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:recursive.I.3',
                        3
                    ],
                    [
                        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:recursive.I.4',
                        4
                    ],
                    [
                        'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:recursive.I.5',
                        250
                    ]
                ],
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.recursive'
        ],
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
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
            'label' => 'LLL:EXT:vd_alertes/Resources/Private/Language/locallang_db.xlf:link'
        ],
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
        'summary' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_alertes/Resources/Private/Language/locallang_db.xlf:summary'
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
        'default_sortby' => 'crdate DESC',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'endtime' => 'endtime',
            'starttime' => 'starttime'
        ],
        'label' => 'title',
        'searchFields' => 'summary,title',
        'title' => 'LLL:EXT:vd_alertes/Resources/Private/Language/locallang_db.xlf:service_alert',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
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
                    summary,
                    depth,
                    link,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
            '
        ]
    ]
];
