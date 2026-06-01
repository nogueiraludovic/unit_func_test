<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'description' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('description'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'email' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,email',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.email'
        ],
        'name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.name'
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
        'www' => [
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
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.www'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'name',
        'delete' => 'deleted',
        'descriptionColumn' => 'description',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'name',
        'searchFields' => 'name',
        'title' => 'LLL:EXT:vd_lada/Resources/Private/Language/locallang_db.xlf:health_network',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'palettes' => [
        'contact' => [
            'showitem' => 'email,telephone'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    name,
                    --palette--;;contact,
                    www,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    description
            '
        ]
    ]
];
