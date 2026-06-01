<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'adresse' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdocosp_adresses',
                'foreign_table_where' => 'ORDER BY tx_vdocosp_adresses.nom',
                'MM' => 'tx_vdocosp_dmde_adresse_mm',
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:addresses'
        ],
        'conditions_admission' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:admission_requirements'
        ],
        'conditions_admission_2' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:admission_requirements_2'
        ],
        'description' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.description'
        ],
        'diplome_complement' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:additional_diploma'
        ],
        'diplome_id' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdocosp_diplomes',
                'foreign_table_where' => 'ORDER BY tx_vdocosp_diplomes.sorting',
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:diploma'
        ],
        'domaine_id' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdocosp_domaines',
                'foreign_table_where' => 'ORDER BY tx_vdocosp_domaines.nom',
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:domain'
        ],
        'domaine_id_2' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdocosp_domaines',
                'foreign_table_where' => 'ORDER BY tx_vdocosp_domaines.nom',
                'items' => [
                    [
                        '',
                        0
                    ]
                ],
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:domain_2'
        ],
        'external_id' => [
            'config' => [
                'default' => 0,
                'eval' => 'int',
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:external_id_on_orientation_ch'
        ],
        'external_link' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkFields' => 'class,params,target',
                            'blindLinkOptions' => 'file,folder,mail,news,news_category,page,tx_vdcontactservice_domain_model_service,tx_vdpressreleases_pressrelease,tx_vdprestations'
                        ]
                    ]
                ],
                'max' => 1024,
                'renderType' => 'inputLink',
                'size' => 50,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:external_link_on_orientation_ch'
        ],
        'formation' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:formation'
        ],
        'formation_2' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:formation_2'
        ],
        'formation_desc' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:formation_descripction'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'hits' => [
            'config' => [
                'default' => 0,
                'eval' => 'int',
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:hits'
        ],
        'indemnites' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:indemnities'
        ],
        'interets' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdocosp_interets',
                'foreign_table_where' => 'ORDER BY tx_vdocosp_interets.nom',
                'minitems' => 1,
                'MM' => 'tx_vdocosp_dmde_interet_mm',
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:interests'
        ],
        'lien_podcast' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkFields' => 'class,params,target',
                            'blindLinkOptions' => 'file,folder,mail,news,news_category,page,tx_vdcontactservice_domain_model_service,tx_vdpressreleases_pressrelease,tx_vdprestations'
                        ]
                    ]
                ],
                'max' => 1024,
                'renderType' => 'inputLink',
                'size' => 50,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:podcast_link'
        ],
        'lieu' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:locations'
        ],
        'lieu_2' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:locations_2'
        ],
        'profession' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:name'
        ],
        'profession_id' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdocosp_professions',
                'foreign_table_where' => 'ORDER BY tx_vdocosp_professions.nom_masc',
                'MM' => 'tx_vdocosp_dmde_professions_mm',
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:profession'
        ],
        'remarques' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:remarks'
        ],
        'remarques_2' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:remarks_2'
        ],
        'remarques_interne' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:internal_remark'
        ],
        'tstamp' => [
            'config' => [
                'default' => 0,
                'type' => 'passthrough'
            ]
        ],
        'video_zoom' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 1024,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:video_zoom'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'profession_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'profession_id',
        'searchFields' => 'conditions_admission,conditions_admission_2,description,diplome_complement,formation,formation_2,formation_desc,indemnites,lieu,lieu_2,profession,remarques,remarques_2,remarques_interne',
        'title' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:careers_schools',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdocosp-dmde'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    profession,
                    profession_id,
                    domaine_id,
                    domaine_id_2,
                    interets,
                    diplome_id,
                    diplome_complement,
                    lien_podcast,
                    video_zoom,
                    description,
                    hits,
                    remarques_interne,
                    salaire_horaire,
                    salaire_mensuel,
                    indemnites,
                    adresse,
                    external_id,
                    external_link,
                --div--;LLL:EXT:vd_ocosp/Resources/Private/Language/Form/locallang_tabs.xlf:formations,
                    formation_desc,
                    formation,
                    lieu,
                    conditions_admission,
                    remarques,
                    formation_2,
                    lieu_2,
                    conditions_admission_2,
                    remarques_2,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
