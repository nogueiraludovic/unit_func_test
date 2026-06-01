<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'address' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'type' => 'text'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:address'
        ],
        'city' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:city'
        ],
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:name'
        ],
        'page_id' => [
            'config' => [
                'allowed' => 'pages',
                'default' => 0,
                'foreign_table' => 'pages',
                'internal_type' => 'db',
                'size' => 1,
                'type' => 'group'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:office_pid'
        ],
        'postal_code' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'size' => 10,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:postal_code'
        ],
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
        'zip_code' => [
            'config' => [
                'default' => '',
                'eval' => \Vd\VdOjvencheres\TCA\Evaluation\ZipEvaluation::class . ',trim,required',
                'max' => 4,
                'size' => 10,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:zip_code'
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
        'label' => 'name',
        'searchFields' => 'address,city,name,postal_code,postal_code,zip_code',
        'title' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:offices',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-x-office'
        ]
    ],
    'palettes' => [
        'access' => [
            'showitem' => 'starttime,endtime'
        ],
        'address' => [
            'showitem' => 'address,--linebreak--,postal_code,zip_code,city'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    name,
                    --palette--;;address,
                    page_id,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
            '
        ]
    ]
];
