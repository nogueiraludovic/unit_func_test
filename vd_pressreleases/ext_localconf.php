<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdPressreleases',
        'Pressrelease',
        [
            \Vd\VdPressreleases\Controller\PressReleaseController::class => 'show'
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_pressreleases/Configuration/TsConfig/Page/Mod/web_list.tsconfig\'
        @import \'EXT:vd_pressreleases/Configuration/TsConfig/Page/Mod/Wizards/NewContentElement.tsconfig\'
        @import \'EXT:vd_pressreleases/Configuration/TsConfig/Page/TceForm/tx_vdpressreleases_domain_model_pressrelease.tsconfig\'
        @import \'EXT:vd_pressreleases/Configuration/TsConfig/Page/TceMain/linkHandler.tsconfig\'
        @import \'EXT:vd_pressreleases/Configuration/TsConfig/Page/TceMain/preview.tsconfig\'
    ');

    $icons = [
        'tx_vdpressreleases-contact' => 'EXT:vd_pressreleases/Resources/Public/Icons/tx_vdpressreleases_domain_model_contact.gif',
        'tx_vdpressreleases-link' => 'EXT:vd_pressreleases/Resources/Public/Icons/tx_vdpressreleases_domain_model_link.gif',
        'tx_vdpressreleases-partnersource' => 'EXT:vd_pressreleases/Resources/Public/Icons/tx_vdpressreleases_domain_model_partnersource.gif',
        'tx_vdpressreleases-pressrelease' => 'EXT:vd_pressreleases/Resources/Public/Icons/tx_vdpressreleases_domain_model_pressrelease.gif',
        'tx_vdpressreleases-pressreleasetype' => 'EXT:vd_pressreleases/Resources/Public/Icons/tx_vdpressreleases_domain_model_pressreleasetype.gif'
    ];

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

    foreach ($icons as $iconIdentifier => $source) {
        $iconRegistry->registerIcon(
            $iconIdentifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            [
                'source' => $source
            ]
        );
    }

    \Vd\VdCore\Utility\CacheUtility::addCacheTagToFlush('tx_vdpressreleases');

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\Vd\VdPressreleases\FormDataProvider\CustomProvider::class]['depends'] = [
        \TYPO3\CMS\Backend\Form\FormDataProvider\TcaColumnsOverrides::class
    ];
})();
