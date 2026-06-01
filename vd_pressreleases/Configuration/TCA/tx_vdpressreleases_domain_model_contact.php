<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'department' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:department'
        ],
        'email' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,email',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:email'
        ],
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'function' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:function'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:name'
        ],
        'phone' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:phone'
        ],
        'service' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:service'
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
        'label_userFunc' => \Vd\VdPressreleases\TCA\Form\LabelRenderer::class . '->forContacts',
        'searchFields' => 'department,email,function,name,phone,service',
        'title' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:contacts',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdpressreleases-contact'
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
                    department,
                    function,
                    service,
                    phone,
                    email,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
                '
        ]
    ]
];
