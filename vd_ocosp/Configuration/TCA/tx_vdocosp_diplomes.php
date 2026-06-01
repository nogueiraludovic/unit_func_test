<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'diplome' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:diploma'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'sorting' => [
            'config' => [
                'default' => 0,
                'type' => 'passthrough'
            ]
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'diplome',
        'searchFields' => 'diplome',
        'sortby' => 'sorting',
        'title' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:diplomas',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdocosp-diplome'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    diplome,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
