<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $GLOBALS['TCA']['tt_content']['columns']['pages']['displayCond']['OR'] = [
        'FIELD:CType:IN:list',
        'AND' => [
            'FIELD:CType:IN:carousel,links_box',
            'OR' =>  [
                'FIELD:type:=:news',
                'FIELD:type:=:press_releases',
                'FIELD:type:=:services'
            ]
        ]
    ];
    $GLOBALS['TCA']['tt_content']['columns']['recursive']['displayCond'] =
        $GLOBALS['TCA']['tt_content']['columns']['pages']['displayCond'];
    $GLOBALS['TCA']['tt_content']['columns']['space_after_class']['config']['default'] = 'mb-5';
    $GLOBALS['TCA']['tt_content']['columns']['space_after_class']['config']['items'] = [
        [
            'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value',
            'mb-5'
        ],
        [
            'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:space_class_none',
            'mb-0'
        ]
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        [
            'cards' => [
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
                    'foreign_field' => 'parent',
                    'foreign_sortby' => 'sorting',
                    'foreign_table' => 'tx_vdsite_domain_model_card',
                    'maxitems' => 30,
                    'type' => 'inline'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:cards'
            ],
            'carousel_items' => [
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
                    'foreign_field' => 'parent',
                    'foreign_sortby' => 'sorting',
                    'foreign_table' => 'tx_vdsite_domain_model_carouselitem',
                    'maxitems' => 30,
                    'type' => 'inline'
                ],
                'displayCond' => 'FIELD:type:=:free',
                'exclude' => true,
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:carousel_items'
            ],
            'content_element_link' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'fieldControl' => [
                        'linkPopup' => [
                            'options' => [
                                'blindLinkFields' => 'class,params,target'
                            ]
                        ]
                    ],
                    'max' => 1024,
                    'renderType' => 'inputLink',
                    'size' => 50,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:link'
            ],
            'content_element_link_text' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'max' => 255,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.text'
            ],
            'limit' => [
                'config' => [
                    'default' => 6,
                    'eval' => 'int',
                    'range' => [
                        'lower' => 1,
                        'upper' => 30
                    ],
                    'size' => 10,
                    'slider' => [
                        'step' => 1,
                        'width' => 200
                    ],
                    'type' => 'input'
                ],
                'displayCond' => [
                    'OR' => [
                        'FIELD:type:=:news',
                        'FIELD:type:=:press_releases',
                        'FIELD:type:=:services'
                    ]
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:limit'
            ],
            'related_links' => [
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
                    'foreign_field' => 'parent',
                    'foreign_sortby' => 'sorting',
                    'foreign_table' => 'tx_vdsite_domain_model_link',
                    'type' => 'inline'
                ],
                'displayCond' => [
                    'OR' => [
                        'FIELD:CType:!IN:carousel,links_box',
                        'AND' => [
                            'FIELD:CType:IN:carousel,links_box',
                            'FIELD:type:=:free'
                        ]
                    ]
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:related_links'
            ],
            'related_records' => [
                'config' => [
                    'allowed' => 'tx_news_domain_model_news,tx_vdpressreleases_domain_model_pressrelease,tx_vdprestations_domain_model_prestation',
                    'default' => '',
                    'internal_type' => 'db',
                    'maxitems' => 30,
                    'type' => 'group'
                ],
                'displayCond' => 'FIELD:type:=:records',
                'exclude' => true,
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:related_records'
            ],
            'services_domain' => [
                'config' => [
                    'default' => '',
                    'disableNoMatchingValueElement' => true,
                    'eval' => 'required',
                    'items' => [
                        [
                            '',
                            ''
                        ]
                    ],
                    'itemsProcFunc' => \Vd\VdPrestations\TCA\Form\ItemRenderer::class . '->forDomains',
                    'minitems' => 1,
                    'renderType' => 'selectSingle',
                    'type' => 'select'
                ],
                'displayCond' => 'FIELD:type:=:services',
                'exclude' => true,
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:services_domain',
                'onChange' => 'reload'
            ],
            'services_theme' => [
                'config' => [
                    'default' => '',
                    'disableNoMatchingValueElement' => true,
                    'eval' => 'required',
                    'items' => [
                        [
                            '',
                            ''
                        ]
                    ],
                    'itemsProcFunc' => \Vd\VdPrestations\TCA\Form\ItemRenderer::class . '->forThemes',
                    'minitems' => 1,
                    'renderType' => 'selectSingle',
                    'type' => 'select'
                ],
                'displayCond' => [
                    'AND' => [
                        'FIELD:services_domain:REQ:true',
                        'FIELD:type:=:services'
                    ]
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:services_theme',
                'onChange' => 'reload'
            ],
            'type' => [
                'config' => [
                    'default' => 'free',
                    'disableNoMatchingValueElement' => true,
                    'eval' => 'required',
                    'items' => [
                        [
                            'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:free',
                            'free'
                        ],
                        [
                            'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:news',
                            'news'
                        ],
                        [
                            'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:press_releases',
                            'press_releases'
                        ],
                        [
                            'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:related_records',
                            'records'
                        ],
                        [
                            'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:related_services',
                            'services'
                        ]
                    ],
                    'minitems' => 1,
                    'renderType' => 'selectSingle',
                    'type' => 'select'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.type',
                'onChange' => 'reload'
            ]
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:CType.I.biocard',
            'biocard',
            'mimetypes-x-content-login',
            'default'
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:CType.I.buttons',
            'buttons',
            'content-menu-thumbnail',
            'default'
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:CType.I.cards_group',
            'cards_group',
            'content-card-group',
            'default'
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:CType.I.carousel',
            'carousel',
            'content-carousel',
            'default'
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:CType.I.citation',
            'citation',
            'content-quote',
            'default'
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:CType.I.links_box',
            'links_box',
            'content-listgroup',
            'default'
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:CType.I.menu_themes',
            'menu_themes',
            'content-text-columns',
            'menu'
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:CType.I.search_form',
            'search_form',
            'content-elements-searchform',
            'forms'
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:vd_site/Configuration/FlexForms/SearchForm.xml',
        'search_form'
    );

    $GLOBALS['TCA']['tt_content']['palettes']['carousel']['showitem'] = '
        type,
        --linebreak--,
        carousel_items,
        services_domain,
        --linebreak--,
        services_theme,
        --linebreak--,
        limit,
        --linebreak--,
        related_records,
        --linebreak--,
        pages,
        --linebreak--,
        recursive,
    ';

    $GLOBALS['TCA']['tt_content']['palettes']['content_element_link']['showitem'] = '
        content_element_link_text,
        --linebreak--,
        content_element_link,
    ';

    $GLOBALS['TCA']['tt_content']['palettes']['related_links']['showitem'] = '
        type,
        --linebreak--,
        related_links,
        services_domain,
        --linebreak--,
        services_theme,
        --linebreak--,
        limit,
        --linebreak--,
        related_records,
        --linebreak--,
        pages,
        --linebreak--,
        recursive,
    ';

    $GLOBALS['TCA']['tt_content']['types']['biocard']['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            header;LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.name,
            bodytext;LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:biography,
            related_links,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.images,
            image,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription
    ';

    $GLOBALS['TCA']['tt_content']['types']['biocard']['columnsOverrides'] = [
        'bodytext' => [
            'config' => [
                'eval' => 'trim,required',
                'rows' => 5
            ]
        ],
        'header' => [
            'config' => [
                'eval' => 'trim,required'
            ]
        ],
        'image' => [
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'appearance' => [
                        'collapseAll' => true,
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'useSortable' => false
                    ],
                    'default' => 0,
                    'maxitems' => 1,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;;minimalPalette,
                                    --palette--;;filePalette
                                '
                            ]
                        ]
                    ]
                ],
                'jpg,jpeg,png,svg'
            )
        ]
    ];

    $GLOBALS['TCA']['tt_content']['types']['buttons']['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
            related_links,
            --palette--;LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:palettes.content_element_link;content_element_link,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription
    ';

    $GLOBALS['TCA']['tt_content']['types']['cards_group']['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
            cards,
            --palette--;LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:palettes.content_element_link;content_element_link,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription
    ';

    $GLOBALS['TCA']['tt_content']['types']['carousel']['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
            --palette--;LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:palettes.carousel;carousel,
            --palette--;LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:palettes.content_element_link;content_element_link,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.extended,
    ';

    $GLOBALS['TCA']['tt_content']['types']['citation']['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            header;LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:author,
            bodytext;LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:citation,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.images,
            image,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription
    ';

    $GLOBALS['TCA']['tt_content']['types']['citation']['columnsOverrides'] =
        $GLOBALS['TCA']['tt_content']['types']['biocard']['columnsOverrides'];

    $GLOBALS['TCA']['tt_content']['types']['links_box']['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            header;LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:title,
            --palette--;LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:palettes.related_links;related_links,
            --palette--;LLL:EXT:vd_site/Resources/Private/Language/locallang_ttc.xlf:palettes.content_element_link;content_element_link,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription
    ';

    $GLOBALS['TCA']['tt_content']['types']['menu_themes']['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription
    ';

    $GLOBALS['TCA']['tt_content']['types']['search_form']['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header.ALT.div_formlabel,
            pi_flexform,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription
    ';

    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['biocard'] = 'mimetypes-x-content-login';
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['buttons'] = 'content-menu-thumbnail';
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['cards_group'] = 'content-card-group';
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['carousel'] = 'content-carousel';
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['citation'] = 'content-quote';
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['links_box'] = 'content-listgroup';
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['menu_themes'] = 'content-text-columns';
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['search_form'] = 'content-elements-searchform';
})();
