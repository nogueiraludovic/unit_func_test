<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'additional_informations' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'readOnly' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:additional_informations'
        ],
        'average_delay' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'readOnly' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:average_delay'
        ],
        'cost' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'readOnly' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:cost'
        ],
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'epayment' => [
            'config' => [
                'default' => false,
                'readOnly' => true,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:epayment'
        ],
        'external_link' => [
            'config' => [
                'default' => false,
                'readOnly' => true,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:external_link'
        ],
        'hash' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 1024,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:hash'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'howto' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'readOnly' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:howto'
        ],
        'required_documents' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'readOnly' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:required_documents'
        ],
        'security_level' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:security_level'
        ],
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
        'type' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:type'
        ],
        'url' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkFields' => 'class,params,target',
                            'blindLinkOptions' => 'file,folder,mail,news,news_category,page,telephone,tx_vdcontactservice_domain_model_service,tx_vdpressreleases_pressrelease,tx_vdprestations'
                        ]
                    ]
                ],
                'max' => 1024,
                'readOnly' => true,
                'renderType' => 'inputLink',
                'size' => 50,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:url'
        ]
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
        'label' => 'type',
        'searchFields' => 'additional_informations,average_delay,cost,hash,howto,required_documents,security_level,type,url',
        'title' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:access_modalities',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdprestations-accessmodality'
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
                    type,
                    howto,
                    additional_informations,
                    average_delay,
                    cost,
                    required_documents,
                    epayment,
                    security_level,
                    external_link,
                    url,
                    hash,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
            '
        ]
    ]
];
