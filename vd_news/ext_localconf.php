<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class)->registerIcon(
        'ext-news-type-alias',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        [
            'source' => 'EXT:vd_news/Resources/Public/Icons/news_domain_model_news_alias.svg'
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_news/Configuration/TsConfig/Page/Mod/web_list.tsconfig\'
        @import \'EXT:vd_news/Configuration/TsConfig/Page/TceMain/linkHandler.tsconfig\'
        @import \'EXT:vd_news/Configuration/TsConfig/Page/TceMain/preview.tsconfig\'
    ');

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdNews',
        'Feed',
        [
            \Vd\VdNews\Controller\FeedController::class => 'rss'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdNews',
        'CategoryList',
        [
            \Vd\VdNews\Controller\CategoryController::class => 'list'
        ]
    );

    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
        ->registerImplementation(
            \GeorgRinger\News\Domain\Model\News::class,
            \Vd\VdNews\Domain\Model\News::class
        );

    $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News'][] = 'vd_news';

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools::class]['flexParsing'][] =
        \Vd\VdNews\Hooks\FlexFormToolsHook::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
        \Vd\VdNews\Hooks\DataHandlerHook::class;
})();
