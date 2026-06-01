<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $icons = [
        'mimetypes-x-item',
        'mimetypes-x-item-category',
        'mimetypes-x-item-condition',
        'mimetypes-x-lot',
        'mimetypes-x-office',
        'mimetypes-x-sale',
        'mimetypes-x-sale-category',
        'mimetypes-x-sale-condition',
        'mimetypes-x-sale-date'
    ];

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

    foreach ($icons as $identifier) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            [
                'source' => 'EXT:vd_ojvencheres/Resources/Public/Icons/mimetypes/' . $identifier . '.png'
            ]
        );
    }

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_ojvencheres/Configuration/TsConfig/Page/Mod/web_list.tsconfig\'
        @import \'EXT:vd_ojvencheres/Configuration/TsConfig/Page/Mod/Wizards/NewContentElement.tsconfig\'
        @import \'EXT:vd_ojvencheres/Configuration/TsConfig/Page/TceForm/tx_vdojvencheres_domain_model_itemcategory.tsconfig\'
        @import \'EXT:vd_ojvencheres/Configuration/TsConfig/Page/TceForm/tx_vdojvencheres_domain_model_sale.tsconfig\'
        @import \'EXT:vd_ojvencheres/Configuration/TsConfig/Page/TceMain/preview.tsconfig\'
    ');

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdOjvencheres',
        'ItemList',
        [
            \Vd\VdOjvencheres\Controller\ItemController::class => 'list,reset'
        ],
        [
            \Vd\VdOjvencheres\Controller\ItemController::class => 'list,reset'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdOjvencheres',
        'ItemShow',
        [
            \Vd\VdOjvencheres\Controller\ItemController::class => 'show'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdOjvencheres',
        'Newsletter',
        [
            \Vd\VdOjvencheres\Controller\ItemController::class => 'newsletter'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdOjvencheres',
        'SaleList',
        [
            \Vd\VdOjvencheres\Controller\SaleController::class => 'list,reset'
        ],
        [
            \Vd\VdOjvencheres\Controller\SaleController::class => 'list,reset'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdOjvencheres',
        'SaleShow',
        [
            \Vd\VdOjvencheres\Controller\SaleController::class => 'show'
        ]
    );

    \Vd\VdCore\Utility\CacheUtility::addCacheTagToFlush('tx_vdojvencheres');

    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = '^tx_vdojvencheres[';
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'e';
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'em';
    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'l';

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
        \Vd\VdOjvencheres\Hooks\DataHandlerHook::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\Vd\VdOjvencheres\TCA\Evaluation\PhoneEvaluation::class]
        = '';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\Vd\VdOjvencheres\TCA\Evaluation\YearEvaluation::class]
        = '';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\Vd\VdOjvencheres\TCA\Evaluation\ZipEvaluation::class]
        = '';

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\Vd\VdOjvencheres\FormDataProvider\TcaColumnsOverrides::class]['depends'] = [
        \TYPO3\CMS\Backend\Form\FormDataProvider\TcaColumnsOverrides::class
    ];
})();
