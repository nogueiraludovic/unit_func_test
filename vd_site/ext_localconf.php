<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class)->registerIcon(
        'content-plain-faq',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        [
            'source' => 'EXT:vd_site/Resources/Public/Icons/PlainFAQ.svg'
        ]
    );

    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Vd\VdSite\Imaging\TcaIconsProvider::class)
        ->addIconsCollections();

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_site/Configuration/TsConfig/Page/Mod/*.tsconfig\'
        @import \'EXT:vd_site/Configuration/TsConfig/Page/Mod/Wizards/NewContentElement.tsconfig\'
        @import \'EXT:vd_site/Configuration/TsConfig/Page/RTE/default.tsconfig\'
        @import \'EXT:vd_site/Configuration/TsConfig/Page/TceMain/permissions.tsconfig\'
        @import \'EXT:vd_site/Configuration/TsConfig/Page/TceForm/*.tsconfig\'
    ');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('
         @import \'EXT:vd_site/Configuration/TsConfig/User/*.tsconfig\'
    ');

    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'] = array_merge(
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'],
        ['gad_campaignid', 'gad_source', 'gbraid', 'requestedurl', 'requestedUrl', 'wbraid', 'XMLReturn']
    );

    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:vd_site/Configuration/RTE/Default.yaml';
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['defaultNoSource'] =
        'EXT:vd_site/Configuration/RTE/DefaultNoSource.yaml';

    unset(
        $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['full'],
        $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['minimal']
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['vdSiteAddTurnstileCaptcha'] =
        \Vd\VdSite\Updates\AddTurnstileCaptchaUpdate::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['vdSiteDeleteAccessibilityMenu'] =
        \Vd\VdSite\Updates\DeleteAccessibilityMenuUpdate::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['vdSiteExecuteSqlQueries'] =
        \Vd\VdSite\Updates\ExecuteSqlQueriesUpdate::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['vdSiteMigrateNewsletter'] =
        \Vd\VdSite\Updates\MigrateNewsletterUpdate::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] =
        \Vd\VdSite\Hooks\PageRendererHook::class . '->addCssFile';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
        \Vd\VdSite\Hooks\DataHandlerHook::class;

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1695108919] = [
        'class' => \Vd\VdSite\Form\Element\InputIconPickerElement::class,
        'nodeName' => 'iconPicker',
        'priority' => 40
    ];
})();
