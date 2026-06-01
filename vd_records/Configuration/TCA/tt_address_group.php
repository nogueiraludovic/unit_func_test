<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'parent_group' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tt_address_group',
                'maxitems' => 2,
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:parent_group'
        ],
        'sorting' => [
            'config' => [
                'default' => 0,
                'type' => 'passthrough'
            ]
        ],
        'title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.title'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'title',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'title',
        'searchFields' => 'title',
        'title' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:address_group',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    title,
                    parent_group,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
