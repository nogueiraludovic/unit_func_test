<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'afp' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'AFP'
        ],
        'cfc' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'CFC'
        ],
        'domain' => [
            'config' => [
                'allowed' => 'tx_vdlisteprofessionsdb_domain',
                'default' => 0,
                'foreign_table' => 'tx_vdlisteprofessionsdb_domain',
                'internal_type' => 'db',
                'maxitems' => 1,
                'MM' => 'tx_vdlisteprofessionsdb_job_domain_mm',
                'size' => 1,
                'type' => 'group'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:activity_domains'
        ],
        'dualjob' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:dual'
        ],
        'fulltime' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:full_time'
        ],
        'learningtime' => [
            'config' => [
                'default' => 0,
                'eval' => 'int',
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:learning_time'
        ],
        'link' => [
            'config' => [
                'allowed' => 'pages',
                'default' => 0,
                'foreign_table' => 'pages',
                'internal_type' => 'db',
                'maxitems' => 1,
                'MM' => 'tx_vdlisteprofessionsdb_job_link_mm',
                'size' => 1,
                'type' => 'group'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:link'
        ],
        'name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.name'
        ],
        'professionalmaturity' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:professional_maturity'
        ],
        'school' => [
            'config' => [
                'allowed' => 'tt_address',
                'default' => 0,
                'foreign_table' => 'tt_address',
                'internal_type' => 'db',
                'maxitems' => 10,
                'MM' => 'tx_vdlisteprofessionsdb_job_school_mm',
                'size' => 3,
                'type' => 'group'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:school'
        ],
        'synonyms' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:synonyms'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'name',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'searchFields' => 'name,synonyms',
        'label' => 'name',
        'title' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:jobs',
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
                    synonyms,
                    domain,
                    school,
                    link,
                    cfc,
                    afp,
                    learningtime,
                    dualjob,
                    fulltime,
                    professionalmaturity,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
