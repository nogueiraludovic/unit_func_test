<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'hour' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'size' => 10,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale_schedule'
        ],
        'sale' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdojvencheres_domain_model_sale',
                'foreign_table_where' => 'AND tx_vdojvencheres_domain_model_sale.pid=###CURRENT_PID###',
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale'
        ],
        'sale_date' => [
            'config' => [
                'default' => 0,
                'eval' => 'date,required',
                'renderType' => 'inputDateTime',
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale_date'
        ],
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
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
        'hideTable' => true,
        'label' => 'sale',
        'label_alt' => 'sale_date',
        'label_alt_force' => true,
        'title' => 'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:sale_date',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-x-sale-date'
        ],
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
                    sale_date,
                    hour,
                    sale,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
            '
        ]
    ]
];
