<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
return [
    'columns' => [
        'address' => [
            'config' => [
                'appearance' => [
                    'collapseAll' => true,
                    'enabledControls' => [
                        'hide' => false,
                        'sort' => false
                    ],
                    'newRecordLinkAddTitle' => true
                ],
                'default' => 0,
                'foreign_field' => 'parent',
                'foreign_table' => 'tt_address',
                'maxitems' => 1,
                'minitems' => 1,
                'type' => 'inline'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.address'
        ],
        'available_places' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdapprenticeship_periods',
                'foreign_table_where' => 'tx_vdapprenticeship_periods.hidden=0',
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_apprenticeship/Resources/Private/Language/locallang.xlf:available_places'
        ],
        'dgav_animals' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_apprenticeship/Resources/Private/Language/locallang.xlf:animals'
        ],
        'dgav_divers' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_apprenticeship/Resources/Private/Language/locallang.xlf:misc'
        ],
        'dgav_farming_products' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_apprenticeship/Resources/Private/Language/locallang.xlf:crops'
        ],
        'dgav_language' => [
            'config' => [
                'default' => false,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'exclude' => true,
            'label' => 'Apprenti CH-Allemand'
        ],
        'dgav_profession' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdapprenticeship_profession',
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
            'label' => 'LLL:EXT:vd_apprenticeship/Resources/Private/Language/locallang.xlf:profession'
        ],
        'dgav_type' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdapprenticeship_type',
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
            'label' => 'LLL:EXT:vd_apprenticeship/Resources/Private/Language/locallang.xlf:exploitation_type'
        ],
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('endtime'),
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'particularities' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_apprenticeship/Resources/Private/Language/locallang.xlf:particularities'
        ],
        'region' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdapprenticeship_region',
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
            'label' => 'LLL:EXT:vd_apprenticeship/Resources/Private/Language/locallang.xlf:region'
        ],
        'slug' => [
            'config' => [
                'default' => '',
                'eval' => 'uniqueInSite',
                'fallbackCharacter' => '-',
                'generatorOptions' => [
                    'fieldSeparator' => '-',
                    'fields' => [
                        'title'
                    ],
                    'prefixParentPageSlug' => true,
                    'replacements' => [
                        '/' => '-'
                    ]
                ],
                'size' => 48,
                'type' => 'slug'
            ],
            'label' => 'LLL:EXT:vd_apprenticeship/Resources/Private/Language/locallang.xlf:slug'
        ],
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('starttime'),
        'title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.title'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'endtime' => 'endtime',
            'starttime' => 'starttime'
        ],
        'label' => 'title',
        'searchFields' => 'dgav_animals,dgav_divers,dgav_farming_products,particularities,title',
        'title' => 'LLL:EXT:vd_apprenticeship/Resources/Private/Language/locallang.xlf:apprenticeships',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'palettes' => [
        'access' => [
            'showitem' => 'starttime,endtime'
        ],
        'dgav' => [
            'showitem' => '
                dgav_profession,
                dgav_type,
                --linebreak--,
                dgav_language,
                --linebreak--,
                dgav_animals,
                dgav_farming_products,
                --linebreak--,
                dgav_divers
            '
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    address,
                    title,
                    slug,
                    region,
                    --palette--;;dgav,
                    available_places,
                    particularities,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden,
                    --palette--;;access
            '
        ]
    ]
];
