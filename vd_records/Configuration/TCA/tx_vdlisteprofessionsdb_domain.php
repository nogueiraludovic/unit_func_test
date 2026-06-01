<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'domain' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 100,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:activity_domain'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden')
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'domain',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'domain',
        'searchFields' => 'domain',
        'title' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:activity_domains',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    domain,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
