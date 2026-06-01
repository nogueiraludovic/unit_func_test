<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'description' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'eval' => 'trim,required',
                'type' => 'text'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:description'
        ],
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:name'
        ],
        'sale_categories' => [
            'config' => [
                'default' => 0,
                'foreign_table' => 'tx_vdojvencheres_domain_model_salecategory',
                'minitems' => 1,
                'MM' => 'tx_vdojvencheres_salecondition_salecategory_mm',
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale_categories'
        ],
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime')
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
        'label' => 'name',
        'searchFields' => 'description,name',
        'title' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale_conditions',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-x-sale-condition'
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
                    name,
                    description,
                    sale_categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
            '
        ]
    ],
];
