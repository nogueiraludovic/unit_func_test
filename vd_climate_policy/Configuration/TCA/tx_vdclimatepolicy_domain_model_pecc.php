<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'color' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'renderType' => 'colorpicker',
                'size' => 10,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_climate_policy/Resources/Private/Language/locallang_tca.xlf:color'
        ],
        'description' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('description'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.name'
        ],
        'number' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_climate_policy/Resources/Private/Language/locallang_tca.xlf:number'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'name',
        'delete' => 'deleted',
        'descriptionColumn' => 'description',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'groupName' => 'vd_climate_policy',
        'hideTable' => true,
        'label' => 'name',
        'searchFields' => 'description,name,number',
        'title' => 'LLL:EXT:vd_climate_policy/Resources/Private/Language/locallang_tca.xlf:peccs',
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
                    color,
                    number,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    description
            '
        ]
    ]
];
