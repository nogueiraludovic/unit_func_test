<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'address' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'description' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('description'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'default' => 0,
                'items' => [
                    [
                        '',
                        0
                    ]
                ],
                'foreign_table' => 'tx_vdclimatepolicy_domain_model_link',
                'foreign_table_where' => 'AND {#tx_vdclimatepolicy_domain_model_link}.{#pid}=###CURRENT_PID### AND {#tx_vdclimatepolicy_domain_model_link}.{#sys_language_uid} IN (-1,0)',
                'renderType' => 'selectSingle',
                'type' => 'select'
            ]
        ],
        'link' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkFields' => 'class,params,target',
                            'blindLinkOptions' => 'folder,mail'
                        ]
                    ]
                ],
                'max' => 1024,
                'renderType' => 'inputLink',
                'size' => 50,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.www'
        ],
        'sys_language_uid' => [
            'config' => [
                'default' => 0,
                'type' => 'language'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language'
        ],
        'text' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_climate_policy/Resources/Private/Language/locallang_tca.xlf:link_text'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'sorting',
        'delete' => 'deleted',
        'descriptionColumn' => 'description',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'groupName' => 'vd_climate_policy',
        'hideTable' => true,
        'label' => 'text',
        'languageField' => 'sys_language_uid',
        'searchFields' => 'description,link,text',
        'sortby' => 'sorting',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'transOrigPointerField' => 'l10n_parent',
        'title' => 'LLL:EXT:vd_climate_policy/Resources/Private/Language/locallang_tca.xlf:links',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    text,
                    link,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;language,
                    hidden,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    description
            '
        ]
    ],
    'palettes' => [
        'language' => [
            'showitem' => 'sys_language_uid,l10n_parent'
        ]
    ]
];
