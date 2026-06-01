<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $icons = [
        'accessmodality' => 'EXT:vd_prestations/Resources/Public/Icons/tx_vdprestations_domain_model_accessmodality.png',
        'prestation' => 'EXT:vd_prestations/Resources/Public/Icons/tx_vdprestations_domain_model_prestation.png',
        'targetaudience' => 'EXT:vd_prestations/Resources/Public/Icons/tx_vdprestations_domain_model_targetaudience.png',
        'url' => 'EXT:vd_prestations/Resources/Public/Icons/tx_vdprestations_domain_model_url.png'
    ];

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

    foreach ($icons as $identifier => $source) {
        $iconRegistry->registerIcon(
            'tx_vdprestations-' . $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            [
                'source' => $source
            ]
        );
    }

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_prestations/Configuration/TsConfig/Page/TceMain/*.tsconfig\'
        @import \'EXT:vd_prestations/Configuration/TsConfig/Page/Mod/web_list.tsconfig\'
    ');

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdPrestations',
        'Pi1',
        [
            \Vd\VdPrestations\Controller\PrestationController::class => 'shortList'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdPrestations',
        'Pi2',
        [
            \Vd\VdPrestations\Controller\PrestationController::class => 'list'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdPrestations',
        'Pi3',
        [
            \Vd\VdPrestations\Controller\PrestationController::class => 'showSearchBar'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdPrestations',
        'Pi4',
        [
            \Vd\VdPrestations\Controller\PrestationController::class => 'show'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdPrestations',
        'Pi5',
        [
            \Vd\VdPrestations\Controller\RoutingController::class => 'redirect'
        ]
    );

    \Vd\VdCore\Utility\CacheUtility::addCacheTagToFlush('tx_vdprestations');

    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'prestation';
    $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['vd_prestations_webservice'] =
        \Vd\VdPrestations\Controller\EsbController::class;

    $GLOBALS['TYPO3_CONF_VARS']['LOG']['Vd']['VdPrestations']['writerConfiguration'] = [
        \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
            \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                'logFile' => getenv('DGNSI_LOG_PATH') . '/prestations/' . date('Y-m-d') . '.log'
            ]
        ]
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
        \Vd\VdPrestations\Hooks\DataHandlerHook::class;
})();
