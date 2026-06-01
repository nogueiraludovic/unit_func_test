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
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.address'
        ],
        'date_examen' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:exam_date'
        ],
        'date_maj' => [
            'config' => [
                'default' => 0,
                'eval' => 'datetime',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'type' => 'input'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:update_date'
        ],
        'delai_inscription' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:subscription_deadline'
        ],
        'diplome' => [
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
        'domaine' => [
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
        'formation' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:formation'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration('hidden'),
        'profession' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdocosp_professions',
                'foreign_table_where' => 'ORDER BY tx_vdocosp_professions.nom_masc',
                'MM' => 'tx_vdocosp_inscriptions_profession_mm',
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'exclude' => true,
            'label' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:professions'
        ],
        'url_exad' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'url_exad',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'useSortable' => false
                    ],
                    'default' => 0,
                    'maxitems' => 1,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_AUDIO => [
                                'showitem' => '
                                    --palette--;;audioOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_VIDEO => [
                                'showitem' => '
                                    --palette--;;videoOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_APPLICATION => [
                                'showitem' => '
                                    --palette--;;imageoverlayPalette,
                                    --palette--;;filePalette
                                '
                            ]
                        ]
                    ]
                ],
                'pdf'
            ),
            'exclude' => true,
            'label' => 'URL exad'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'formation',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'label' => 'formation',
        'searchFields' => 'date_examen,delai_inscription,formation',
        'title' => 'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_db.xlf:subscriptions',
        'tstamp' => 'tstamp',
        'typeicon_classes' => [
            'default' => 'tx_vdocosp-inscription'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    adresse,
                    diplome,
                    domaine,
                    formation,
                    delai_inscription,
                    date_examen,
                    date_maj,
                    profession,
                    url_exad,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    hidden
            '
        ]
    ]
];
