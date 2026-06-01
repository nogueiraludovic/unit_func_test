<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'code' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 25,
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:code'
        ],
        'department_id' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vddptservices_departments',
                'foreign_table_where' => 'ORDER BY tx_vddptservices_departments.uid',
                'items' => [
                    [
                        '',
                        0
                    ]
                ],
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:department_id'
        ],
        'label' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:label'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'department_id, code',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'code',
        'label_alt' => 'label',
        'label_alt_force' => true,
        'searchFields' => 'code,label',
        'title' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:services',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    code,
                    label,
                    department_id,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
