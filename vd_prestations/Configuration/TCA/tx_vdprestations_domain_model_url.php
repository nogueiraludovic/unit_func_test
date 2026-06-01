<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'fieldname' => [
            'config' => [
                'default' => '',
                'type' => 'passthrough'
            ]
        ],
        'hash' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 1024,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:hash'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
        'title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.title'
        ],
        'url' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkFields' => 'class,params,target',
                            'blindLinkOptions' => 'file,folder,mail,news,news_category,page,telephone,tx_vdcontactservice_domain_model_service,tx_vdpressreleases_pressrelease,tx_vdprestations'
                        ]
                    ]
                ],
                'max' => 1024,
                'readOnly' => true,
                'renderType' => 'inputLink',
                'size' => 50,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:url'
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
        'hideTable' => true,
        'label' => 'title',
        'searchFields' => 'hash,title,url',
        'title' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:urls',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdprestations-url'
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
                    url,
                    hash,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
            '
        ]
    ]
];
