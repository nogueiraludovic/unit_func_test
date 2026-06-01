<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
$configuration = [
    'columns' => [
        'additional_contents' => [
            'config' => [
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'newRecordLinkAddTitle' => true
                ],
                'default' => 0,
                'foreign_field' => 'press_release',
                'foreign_match_fields' => [
                    'fieldname' => 'additional_contents'
                ],
                'foreign_table' => 'tx_vdpressreleases_domain_model_link',
                'type' => 'inline'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:additional_contents'
        ],
        'additional_text' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'eval' => 'trim',
                'type' => 'text'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:additional_text'
        ],
        'anonymize' => [
            'config' => [
                'default' => true,
                'renderType' => 'checkboxToggle',
                'type' => 'check'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:anonymize',
            'onChange' => 'reload'
        ],
        'anonymize_time' => [
            'config' => [
                'default' => 0,
                'eval' => 'datetime,int',
                'renderType' => 'inputDateTime',
                'type' => 'input'
            ],
            'displayCond' => [
                'AND' => [
                    'FIELD:anonymize:REQ:true',
                    'FIELD:type:>:0'
                ]
            ],
            'exclude' => true,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:anonymize_time'
        ],
        'body_text' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'eval' => 'trim',
                'type' => 'text'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:body_text'
        ],
        'contacts' => [
            'config' => [
                'default' => 0,
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => false,
                        'options' => [
                            'pid' => 2000101
                        ]
                    ],
                    'editPopup' => [
                        'disabled' => false
                    ],
                    'listModule' => [
                        'disabled' => false,
                        'options' => [
                            'pid' => 2000101
                        ]
                    ]
                ],
                'foreign_table' => 'tx_vdpressreleases_domain_model_contact',
                'foreign_table_where' => 'AND {#tx_vdpressreleases_domain_model_contact}.{#pid}=2000101',
                'MM' => 'tx_vdpressreleases_pressrelease_contact_mm',
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:contacts'
        ],
        'crdate' => [
            'config' => [
                'default' => 0,
                'eval' => 'datetime,int',
                'readOnly' => true,
                'renderType' => 'inputDateTime',
                'type' => 'input'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:crdate'
        ],
        'date_time' => [
            'config' => [
                'default' => 0,
                'eval' => 'datetime,int,required',
                'renderType' => 'inputDateTime',
                'type' => 'input'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:date_time'
        ],
        'endtime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration(
            'endtime',
            [
                'displayCond' => 'FIELD:type:>:0'
            ]
        ),
        'files' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'files',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'expandSingle' => true
                    ],
                    'default' => 0,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN => [
                                'showitem' => '
                                    --palette--;;pressReleaseOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;;pressReleaseOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;pressReleaseOverlayPalette,
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
                                    --palette--;;pressReleaseOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ]
                        ]
                    ]
                ],
                'pdf'
            ),
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:files'
        ],
        'forcedpdf_file' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'forcedpdf_file',
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
                                'showitem' => '--palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_TEXT => [
                                'showitem' => '--palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '--palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_AUDIO => [
                                'showitem' => '--palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_VIDEO => [
                                'showitem' => '--palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_APPLICATION => [
                                'showitem' => '--palette--;;filePalette'
                            ]
                        ]
                    ]
                ],
                'pdf'
            ),
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:forcedpdf_file'
        ],
        'hidden' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration(
            'hidden',
            [
                'config' => [
                    'default' => true
                ],
                'displayCond' => 'FIELD:type:>:0'
            ]
        ),
        'images' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'images',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'expandSingle' => true
                    ],
                    'default' => 0,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN => [
                                'showitem' => '
                                    --palette--;;pressReleaseOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;;pressReleaseOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;pressReleaseOverlayPalette,
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
                                    --palette--;;pressReleaseOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ]
                        ]
                    ]
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:images'
        ],
        'keywords' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'type' => 'text'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:keywords'
        ],
        'partner_sources' => [
            'config' => [
                'default' => 0,
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => false,
                        'options' => [
                            'pid' => 2000102
                        ]
                    ],
                    'editPopup' => [
                        'disabled' => false
                    ],
                    'listModule' => [
                        'disabled' => false,
                        'options' => [
                            'pid' => 2000102
                        ]
                    ]
                ],
                'foreign_table' => 'tx_vdpressreleases_domain_model_partnersource',
                'foreign_table_where' => 'AND {#tx_vdpressreleases_domain_model_partnersource}.{#pid}=2000102',
                'MM' => 'tx_vdpressreleases_pressrelease_partnersource_mm',
                'renderType' => 'selectMultipleSideBySide',
                'type' => 'select'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:partner_sources'
        ],
        'partner_source_infos' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:partner_source_infos'
        ],
        'path_segment' => [
            'config' => [
                'default' => '',
                'eval' => 'unique',
                'fallbackCharacter' => '-',
                'generatorOptions' => [
                    'fields' => [
                        'title',
                        'crdate'
                    ],
                    'fieldSeparator' => '-',
                    'replacements' => [
                        '/' => '-'
                    ]
                ],
                'max' => 2048,
                'size' => 50,
                'type' => 'slug'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:path_segment'
        ],
        'publidoc_files' => [
            'config' => [
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'newRecordLinkAddTitle' => true
                ],
                'default' => 0,
                'foreign_field' => 'press_release',
                'foreign_match_fields' => [
                    'fieldname' => 'publidoc_files'
                ],
                'foreign_table' => 'tx_vdpressreleases_domain_model_link',
                'type' => 'inline'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:publidoc_files'
        ],
        'signature' => [
            'config' => [
                'default' => 'Bureau d\'information et de communication de l\'État de Vaud',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:signature'
        ],
        'source' => [
            'config' => [
                'default' => 'État de Vaud',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:source'
        ],
        'source_id' => [
            'config' => [
                'default' => 0,
                'eval' => 'int',
                'readOnly' => true,
                'size' => 10,
                'type' => 'input'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:source_id'
        ],
        'starttime' => \Vd\VdCore\Utility\TcaUtility::getFieldConfiguration(
            'starttime',
            [
                'displayCond' => 'FIELD:type:>:0'
            ]
        ),
        'subtitle' => [
            'config' => [
                'default' => '',
                'eval' => 'trim',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:subtitle'
        ],
        'summary' => [
            'config' => [
                'default' => '',
                'enableRichtext' => true,
                'eval' => 'trim',
                'type' => 'text'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:summary'
        ],
        'title' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'type' => 'input'
            ],
            'displayCond' => 'FIELD:type:>:0',
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:title'
        ],
        'type' => [
            'config' => [
                'default' => 0,
                'disableNoMatchingValueElement' => true,
                'foreign_table' => 'tx_vdpressreleases_domain_model_pressreleasetype',
                'items' => [
                    [
                        '',
                        0
                    ]
                ],
                'minitems' => 1,
                'renderType' => 'selectSingle',
                'type' => 'select'
            ],
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:type',
            'onChange' => 'reload'
        ],
        'videos' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'videos',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference',
                        'expandSingle' => true
                    ],
                    'default' => 0,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN => [
                                'showitem' => '
                                    --palette--;;pressReleaseOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_TEXT => [
                                'showitem' => '
                                    --palette--;;pressReleaseOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ],
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;pressReleaseOverlayPalette,
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
                                    --palette--;;pressReleaseOverlayPalette,
                                    --palette--;;filePalette
                                '
                            ]
                        ]
                    ]
                ],
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext']
            ),
            'displayCond' => 'FIELD:type:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:videos'
        ]
    ],
    'ctrl' => [
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'default_sortby' => 'date_time DESC',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'endtime' => 'endtime',
            'starttime' => 'starttime'
        ],
        'hideAtCopy' => true,
        'label' => 'title',
        'searchFields' => 'additional_text,body_text,keywords,partner_source_infos,signature,source,subtitle,summary,title',
        'title' => 'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:press_releases',
        'tstamp' => 'tstamp',
        'type' => 'type',
        'typeicon_classes' => [
            'default' => 'tx_vdpressreleases-pressrelease'
        ]
    ],
    'palettes' => [
        'access' => [
            'showitem' => 'starttime,endtime'
        ],
        'anonymization' => [
            'showitem' => 'anonymize,anonymize_time'
        ],
        'source' => [
            'showitem' => 'source,source_id'
        ],
        'title' => [
            'showitem' => 'subtitle,--linebreak--,title'
        ]
    ]
];

for ($i = 1; $i < 10; ++$i) {
    if ($i === 7) {
        continue;
    }

    $configuration['types'][$i]['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            type,
            --palette--;;title,
            date_time,
            path_segment,
        --div--;LLL:EXT:vd_pressreleases/Resources/Private/Language/Form/locallang_tabs.xlf:texts,
            summary,
            body_text,
            additional_text,
            signature,
        --div--;LLL:EXT:vd_pressreleases/Resources/Private/Language/Form/locallang_tabs.xlf:transmitter,
            --palette--;;source,
            partner_sources,
            partner_source_infos,
            contacts,
            --palette--;;anonymization,
        --div--;LLL:EXT:vd_pressreleases/Resources/Private/Language/Form/locallang_tabs.xlf:medias,
            files,
            images,
            videos,
            forcedpdf_file,
        --div--;LLL:EXT:vd_pressreleases/Resources/Private/Language/Form/locallang_tabs.xlf:links,
            additional_contents,
            publidoc_files,
        --div--;LLL:EXT:vd_pressreleases/Resources/Private/Language/Form/locallang_tabs.xlf:seo,
            keywords,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            crdate
    ';
}

$configuration['types'][7]['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        type,
        title,
        date_time,
        path_segment,
    --div--;LLL:EXT:vd_pressreleases/Resources/Private/Language/Form/locallang_tabs.xlf:texts,
        summary,
    --div--;LLL:EXT:vd_pressreleases/Resources/Private/Language/Form/locallang_tabs.xlf:medias,
        forcedpdf_file,
    --div--;LLL:EXT:vd_pressreleases/Resources/Private/Language/Form/locallang_tabs.xlf:seo,
        keywords,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        hidden,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
        crdate
';

return $configuration;
