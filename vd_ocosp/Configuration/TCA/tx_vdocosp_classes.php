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
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.description'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'nom' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:name'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'nom',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'nom',
        'searchFields' => 'description,nom',
        'title' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:classes',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdocosp-classe'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    nom,
                    description,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
