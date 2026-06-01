<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $icons = [
        'tx_vdocosp-adresse' => 'Adresse',
        'tx_vdocosp-classe' => 'Classe',
        'tx_vdocosp-diplome' => 'Diplome',
        'tx_vdocosp-dmde' => 'Dmde',
        'tx_vdocosp-domaine' => 'Domaine',
        'tx_vdocosp-im-domaine' => 'Domaine',
        'tx_vdocosp-inscription' => 'Inscription',
        'tx_vdocosp-interet' => 'Interet',
        'tx_vdocosp-profession' => 'Profession'
    ];

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

    foreach ($icons as $identifier => $name) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            [
                'source' => 'EXT:vd_ocosp/Resources/Public/Icons/' . $name . '.png'
            ]
        );
    }

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_ocosp/Configuration/TsConfig/Page/Mod/web_list.tsconfig\'
    ');

    $plugins = [
        'DisplayAdresses' => [
            'action' => 'index,detail',
            'controller' => \Vd\VdOcosp\Controller\Display\AdresseController::class,
            'nonCacheableAction' => 'index'
        ],
        'DisplayApprentissages' => [
            'action' => 'index',
            'controller' => \Vd\VdOcosp\Controller\Display\ApprentissageController::class
        ],
        'DisplayDmdeAdvancedSearch' => [
            'action' => 'index,detail,search',
            'controller' => \Vd\VdOcosp\Controller\Display\DmdeController::class,
            'nonCacheableAction' => 'search'
        ],
        'DisplayDmdeSimpleSearch' => [
            'action' => 'simpleForm',
            'controller' => \Vd\VdOcosp\Controller\Display\DmdeController::class,
            'nonCacheableAction' => 'simpleForm'
        ],
        'DisplayInscription' => [
            'action' => 'index',
            'controller' => \Vd\VdOcosp\Controller\Display\InscriptionController::class,
            'nonCacheableAction' => 'index'
        ],
        'DisplayProfession' => [
            'action' => 'index,detail',
            'controller' => \Vd\VdOcosp\Controller\Display\ProfessionController::class,
            'nonCacheableAction' => 'index'
        ],
        'DisplaySalary' => [
            'action' => 'index,export',
            'controller' => \Vd\VdOcosp\Controller\Display\SalaryController::class
        ]
    ];

    foreach ($plugins as $pluginName => $pluginConfiguration) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'VdOcosp',
            $pluginName,
            [
                $pluginConfiguration['controller'] => $pluginConfiguration['action']
            ],
            [
                $pluginConfiguration['controller'] => $pluginConfiguration['nonCacheableAction']
            ]
        );
    }

    \Vd\VdCore\Utility\CacheUtility::addCacheTagToFlush('tx_vdocosp');

    $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = '^tx_vdocosp[';
})();
