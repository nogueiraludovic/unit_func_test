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
        'department' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'eval' => 'required',
                'foreign_table' => 'tx_vdcontactservice_domain_model_department',
                'foreign_table_where' => 'ORDER BY tx_vdcontactservice_domain_model_department.name',
                'items' => [
                    [
                        '',
                        ''
                    ]
                ],
                'minitems' => 1,
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:department'
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
        'reference_page' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkFields' => 'class,params,target',
                            'blindLinkOptions' => 'file,folder,mail,news,telephone,tx_vdcontactservice_domain_model_service,tx_vdpressreleases_pressrelease,tx_vdprestations'
                        ]
                    ]
                ],
                'max' => 1024,
                'renderType' => 'inputLink',
                'size' => 50,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:reference_page'
        ],
        'service_contacts' => [
            'config' => [
                'appearance' => [
                    'collapseAll' => true,
                    'enabledControls' => [
                        'sort' => false
                    ],
                    'expandSingle' => true,
                    'newRecordLinkAddTitle' => true,
                    'useSortable' => true
                ],
                'default' => 0,
                'foreign_field' => 'service',
                'foreign_table' => 'tx_vdcontactservice_domain_model_servicecontact',
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
        'title' => 'LLL:EXT:vd_contact_service/Resources/Private/Language/locallang_db.xlf:service',
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
                    department,
                    reference_page,
                    service_contacts,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
