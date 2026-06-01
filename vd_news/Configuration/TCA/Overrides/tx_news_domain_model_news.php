<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['externalurl']['config']['fieldControl']['linkPopup']['options']['blindLinkFields'] =
        'class,params,target,title';
    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['externalurl']['config']['fieldControl']['linkPopup']['options']['blindLinkOptions'] =
        'file,folder,mail,news,news_category,page,tx_vdcontactservice_domain_model_service,tx_vdpressreleases_pressrelease,tx_vdprestations';
    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['externalurl']['config']['renderType'] = 'inputLink';
    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['hidden']['config']['default'] = true;
    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['internalurl']['config']['fieldControl']['linkPopup']['options']['blindLinkFields'] =
        'class,params,target,title';
    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['internalurl']['config']['fieldControl']['linkPopup']['options']['blindLinkOptions'] =
        'file,folder,mail,news,news_category,telephone,tx_vdcontactservice_domain_model_service,tx_vdpressreleases_pressrelease,tx_vdprestations,url';
    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['path_segment']['config']['generatorOptions']['fieldSeparator'] =
        '-';
    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['path_segment']['config']['generatorOptions']['postModifiers'][] =
        \Vd\VdNews\Routing\NewsSlugModifier::class . '->modify';
    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['related']['config']['filter'] = [
        [
            'userFunc' => \Vd\VdNews\TCA\Form\FilterProvider::class . '->forNews'
        ]
    ];
    $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type']['config']['items'][] = [
        'LLL:EXT:vd_news/Resources/Private/Language/locallang_be.xlf:alias',
        3,
        'ext-news-type-alias'
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tx_news_domain_model_news',
        [
            'selected_article' => [
                'config' => [
                    'allowed' => 'tx_news_domain_model_news',
                    'filter' => [
                        [
                            'userFunc' => \Vd\VdNews\TCA\Form\FilterProvider::class . '->forNews'
                        ]
                    ],
                    'foreign_table' => 'tx_news_domain_model_news',
                    'internal_type' => 'db',
                    'maxitems' => 1,
                    'minitems' => 1,
                    'size' => 1,
                    'suggestOptions' => [
                        'tx_news_domain_model_news' => [
                            'addWhere' => 'AND tx_news_domain_model_news.uid<>###THIS_UID###'
                        ]
                    ],
                    'type' => 'group'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_news/Resources/Private/Language/locallang_be.xlf:target'
            ]
        ]
    );

    $GLOBALS['TCA']['tx_news_domain_model_news']['ctrl']['label_userFunc'] =
        \Vd\VdNews\TCA\Form\LabelProvider::class . '->forNews';
    $GLOBALS['TCA']['tx_news_domain_model_news']['ctrl']['typeicon_classes'][] = 'ext-news-type-alias';

    $GLOBALS['TCA']['tx_news_domain_model_news']['types'][3]['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            type,
            selected_article,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;paletteHidden
    ';
})();
