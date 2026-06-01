<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'access_modalities' => [
            'config' => [
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'newRecordLinkAddTitle' => true
                ],
                'default' => 0,
                'foreign_table' => 'tx_vdprestations_domain_model_accessmodality',
                'readOnly' => true,
                'type' => 'inline'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:access_modalities'
        ],
        'action_client' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:action_client'
        ],
        'action_service' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:action_service'
        ],
        'description' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'readOnly' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.description'
        ],
        'domain_id' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:domain_id'
        ],
        'domain_name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:domain_name'
        ],
        'emolument' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'readOnly' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:emolument'
        ],
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'external_id' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:external_id'
        ],
        'help_link' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:help_link',
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'legal_references' => [
            'config' => [
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'newRecordLinkAddTitle' => true
                ],
                'default' => 0,
                'foreign_match_fields' => [
                    'fieldname' => 'legal_references'
                ],
                'foreign_table' => 'tx_vdprestations_domain_model_url',
                'readOnly' => true,
                'type' => 'inline'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:legal_references'
        ],
        'path_segment' => [
            'config' => [
                'default' => '',
                'eval' => 'unique',
                'fallbackCharacter' => '-',
                'generatorOptions' => [
                    'fields' => [
                        'title'
                    ],
                    'replacements' => [
                        '/' => '-'
                    ],
                ],
                'size' => 50,
                'type' => 'slug'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug'
        ],
        'prerequisites' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'readOnly' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:prerequisites'
        ],
        'related_pages' => [
            'config' => [
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'newRecordLinkAddTitle' => true
                ],
                'default' => 0,
                'foreign_match_fields' => [
                    'fieldname' => 'related_pages'
                ],
                'foreign_table' => 'tx_vdprestations_domain_model_url',
                'readOnly' => true,
                'type' => 'inline'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:related_pages'
        ],
        'related_prestations' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdprestations_domain_model_prestation',
                'MM' => 'tx_vdprestations_prestation_prestation_mm',
                'readOnly' => true,
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:related_prestations'
        ],
        'result' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:result'
        ],
        'security' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 24,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:security'
        ],
        'service_name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:service_name'
        ],
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
        'target_audience' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdprestations_domain_model_targetaudience',
                'readOnly' => true,
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:target_audience'
        ],
        'theme_id' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:theme_id'
        ],
        'theme_name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:theme_name'
        ],
        'title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 1024,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.title'
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
        'label' => 'external_id',
        'label_userFunc' => \Vd\VdPrestations\TCA\Form\LabelRenderer::class . '->forService',
        'searchFields' => 'action_client,action_service,description,domain_id,domain_name,emolument,external_id,help_link,path_segment,prerequisites,result,service_name,theme_id,theme_name,title',
        'title' => 'LLL:EXT:vd_prestations/Resources/Private/Language/locallang_db.xlf:prestations',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdprestations-prestation'
        ]
    ],
    'palettes' => [
        'access' => [
            'showitem' => 'starttime,endtime'
        ],
        'action' => [
            'showitem' => 'action_client,action_service'
        ],
        'domain' => [
            'showitem' => 'domain_name,domain_id'
        ],
        'theme' => [
            'showitem' => 'theme_name,theme_id'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    title,
                    path_segment,
                    external_id,
                    description,
                    --palette--;;domain,
                    --palette--;;theme,
                    target_audience,
                    emolument,
                    prerequisites,
                    related_prestations,
                    related_pages,
                    legal_references,
                    access_modalities,
                    service_name,
                    result,
                    --palette--;;action,
                    help_link,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
            '
        ]
    ]
];
