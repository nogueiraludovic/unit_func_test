<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class);
    $registry->configureContainer(
        (
            new \B13\Container\Tca\ContainerConfiguration(
                '1-column-container',
                'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:one_column',
                '',
                [
                    [
                        [
                            'colPos' => 200,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:main'
                        ]
                    ]
                ]
            )
        )
            ->setDefaultValues([
                'header' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:one_column'
            ])
            ->setGroup('container')
            ->setIcon('EXT:container/Resources/Public/Icons/container-1col.svg')
    );

    $registry->configureContainer(
        (
            new \B13\Container\Tca\ContainerConfiguration(
                '2-columns-left-width-container',
                'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:two_columns_left',
                '',
                [
                    [
                        [
                            'colPos' => 200,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:left'
                        ],
                        [
                            'colPos' => 210,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:right'
                        ]
                    ]
                ]
            )
        )
            ->setDefaultValues([
                'header' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:two_columns_left'
            ])
            ->setGroup('container')
            ->setIcon('EXT:container/Resources/Public/Icons/container-2col-left.svg')
    );

    $registry->configureContainer(
        (
            new \B13\Container\Tca\ContainerConfiguration(
                '2-columns-right-width-container',
                'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:two_columns_right',
                '',
                [
                    [
                        [
                            'colPos' => 200,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:left'
                        ],
                        [
                            'colPos' => 210,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:right'
                        ]
                    ]
                ]
            )
        )
            ->setDefaultValues([
                'header' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:two_columns_right'
            ])
            ->setGroup('container')
            ->setIcon('EXT:container/Resources/Public/Icons/container-2col-right.svg')
    );

    $registry->configureContainer(
        (
            new \B13\Container\Tca\ContainerConfiguration(
                '2-columns-same-width-container',
                'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:two_columns_same',
                '',
                [
                    [
                        [
                            'colPos' => 200,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:left'
                        ],
                        [
                            'colPos' => 210,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:right'
                        ]
                    ]
                ]
            )
        )
            ->setDefaultValues([
                'header' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:two_columns_same'
            ])
            ->setGroup('container')
            ->setIcon('EXT:container/Resources/Public/Icons/container-2col.svg')
    );

    $registry->configureContainer(
        (
            new \B13\Container\Tca\ContainerConfiguration(
                '3-columns-same-width-container',
                'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:three_columns_same',
                '',
                [
                    [
                        [
                            'colPos' => 200,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:left'
                        ],
                        [
                            'colPos' => 210,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:center'
                        ],
                        [
                            'colPos' => 220,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:right'
                        ]
                    ]
                ]
            )
        )
            ->setDefaultValues([
                'header' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:three_columns_same'
            ])
            ->setGroup('container')
            ->setIcon('EXT:container/Resources/Public/Icons/container-3col.svg')
    );

    $registry->configureContainer(
        (
            new \B13\Container\Tca\ContainerConfiguration(
                '4-columns-same-width-container',
                'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:four_columns_same',
                '',
                [
                    [
                        [
                            'colPos' => 200,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:left'
                        ],
                        [
                            'colPos' => 210,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:center_left'
                        ],
                        [
                            'colPos' => 220,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:center_right'
                        ],
                        [
                            'colPos' => 230,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:right'
                        ]
                    ]
                ]
            )
        )
            ->setDefaultValues([
                'header' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:four_columns_same'
            ])
            ->setGroup('container')
            ->setIcon('EXT:container/Resources/Public/Icons/container-4col.svg')
    );

    $registry->configureContainer(
        (
            new \B13\Container\Tca\ContainerConfiguration(
                'accordion-container',
                'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:accordion',
                '',
                [
                    [
                        [
                            'allowed' => [
                                'CType' => 'image,text,textpic'
                            ],
                            'colPos' => 200,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:main'
                        ]
                    ]
                ]
            )
        )
            ->setDefaultValues([
                'header' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:accordion'
            ])
            ->setGroup('default')
            ->setIcon('content-accordion')
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:vd_container/Configuration/FlexForms/Accordion.xml',
        'accordion-container'
    );

    $GLOBALS['TCA']['tt_content']['types']['accordion-container']['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
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

    $registry->configureContainer(
        (
            new \B13\Container\Tca\ContainerConfiguration(
                'highlighting-container',
                'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:highlight',
                '',
                [
                    [
                        [
                            'colPos' => 200,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:main'
                        ]
                    ]
                ]
            )
        )
            ->setDefaultValues([
                'header' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:highlight'
            ])
            ->setGroup('default')
            ->setIcon('EXT:container/Resources/Public/Icons/container-1col.svg')
    );

    $GLOBALS['TCA']['tt_content']['types']['highlighting-container']['showitem'] = '
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

    $registry->configureContainer(
        (
            new \B13\Container\Tca\ContainerConfiguration(
                'section-container',
                'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:section_background_color',
                '',
                [
                    [
                        [
                            'colPos' => 200,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:main'
                        ]
                    ]
                ]
            )
        )
            ->setDefaultValues([
                'header' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:section_background_color'
            ])
            ->setGroup('container')
            ->setIcon('EXT:container/Resources/Public/Icons/container-1col.svg')
    );

    $registry->configureContainer(
        (
            new \B13\Container\Tca\ContainerConfiguration(
                'tabs-container',
                'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:tabs',
                '',
                [
                    [
                        [
                            'allowed' => [
                                'CType' => 'text'
                            ],
                            'colPos' => 200,
                            'name' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:main'
                        ]
                    ]
                ]
            )
        )
            ->setDefaultValues([
                'header' => 'LLL:EXT:vd_container/Resources/Private/Language/locallang_be.xlf:tabs'
            ])
            ->setGroup('default')
            ->setIcon('EXT:container/Resources/Public/Icons/container-1col.svg')
    );

    $GLOBALS['TCA']['tt_content']['types']['tabs-container']['showitem'] = '
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
})();
