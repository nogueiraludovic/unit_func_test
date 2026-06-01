<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
        'title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:title'
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
        'searchFields' => 'title',
        'title' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:types',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdpressreleases-pressreleasetype'
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
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
                '
        ]
    ]
];
