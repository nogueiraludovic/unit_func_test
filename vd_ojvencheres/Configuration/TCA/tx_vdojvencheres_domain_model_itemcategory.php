<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'keywords' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:keywords'
        ],
        'name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:name'
        ],
        'parent' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdojvencheres_domain_model_itemcategory',
                'foreign_table_where' => "AND (('###PAGE_TSCONFIG_STR###'='secondary' AND tx_vdojvencheres_domain_model_itemcategory.parent=1) OR ('###PAGE_TSCONFIG_STR###'='main' AND tx_vdojvencheres_domain_model_itemcategory.parent=0))",
                'items' => [
                    [
                        '',
                        0
                    ]
                ],
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:parent_category'
        ],
        'selectable' => [
            'config' => [
                'default' => true,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:selectable'
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
        'searchFields' => 'keywords,name',
        'title' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:item_categories',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-x-item-category'
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
                    parent,
                    selectable,
                    keywords,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
            '
        ]
    ]
];
