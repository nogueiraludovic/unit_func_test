<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
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
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime')
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
        'searchFields' => 'name',
        'title' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale_categories',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-x-sale-category'
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
                    name,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
            '
        ]
    ],
];
