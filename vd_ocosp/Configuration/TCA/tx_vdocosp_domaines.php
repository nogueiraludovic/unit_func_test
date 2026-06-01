<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'delais' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:deadline'
        ],
        'demande' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:demand'
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
        'searchFields' => 'nom',
        'title' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:domains',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdocosp-domaine'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    nom,
                    demande,
                    delais,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
