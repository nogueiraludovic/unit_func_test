<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'code' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:code'
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
        'services' => [
            'config' => [
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'newRecordLinkAddTitle' => true
                ],
                'default' => 0,
                'foreign_field' => 'department',
                'foreign_table' => 'tx_vdcontactservice_domain_model_service',
                'type' => 'inline'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:services'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'code',
        'label_alt' => 'name',
        'label_alt_force' => true,
        'searchFields' => 'code,name',
        'title' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:department',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'palettes' => [
        'name' => [
            'showitem' => 'code,name'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;name,
                    services,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
