<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'id_district' => [
            'config' => [
                'default' => 0,
                'eval' => 'int,required',
                'size' => 10,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:id_district'
        ],
        'name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.name'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'name',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'name',
        'label_alt' => 'id_district',
        'label_alt_force' => true,
        'rootLevel' => true,
        'searchFields' => 'name',
        'title' => 'LLL:EXT:vd_municipalities/Resources/Private/Language/locallang_db.xlf:districts',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    name,
                    id_district,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
