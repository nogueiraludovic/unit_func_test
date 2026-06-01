<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $plugins = [
        'DecisionShow' => [
            'action' => 'show',
            'controller' => \Vd\VdSafarinet\Controller\DecisionController::class
        ],
        'GcList' => [
            'action' => 'gcList',
            'controller' => \Vd\VdSafarinet\Controller\MeetingController::class,
            'nonCacheableAction' => 'gcList'
        ],
        'GcMeetingShow' => [
            'action' => 'gcMeetingShow',
            'controller' => \Vd\VdSafarinet\Controller\MeetingController::class
        ],
        'GcPointShow' => [
            'action' => 'gcPointShow',
            'controller' => \Vd\VdSafarinet\Controller\MeetingController::class
        ],
        'GroupShow' => [
            'action' => 'show',
            'controller' => \Vd\VdSafarinet\Controller\GroupController::class
        ],
        'MeetingList' => [
            'action' => 'list',
            'controller' => \Vd\VdSafarinet\Controller\MeetingController::class,
            'nonCacheableAction' => 'list'
        ],
        'MeetingShow' => [
            'action' => 'show',
            'controller' => \Vd\VdSafarinet\Controller\MeetingController::class
        ],
        'MemberDistrict' => [
            'action' => 'district',
            'controller' => \Vd\VdSafarinet\Controller\MemberController::class
        ],
        'MemberList' => [
            'action' => 'list',
            'controller' => \Vd\VdSafarinet\Controller\MemberController::class
        ],
        'MemberParty' => [
            'action' => 'party',
            'controller' => \Vd\VdSafarinet\Controller\MemberController::class
        ],
        'MemberShow' => [
            'action' => 'show',
            'controller' => \Vd\VdSafarinet\Controller\MemberController::class
        ],
        'ObjectShow' => [
            'action' => 'show',
            'controller' => \Vd\VdSafarinet\Controller\ObjectController::class
        ]
    ];

    foreach ($plugins as $pluginName => $pluginConfiguration) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'VdSafarinet',
            $pluginName,
            [
                $pluginConfiguration['controller'] => $pluginConfiguration['action']
            ],
            [
                $pluginConfiguration['controller'] => $pluginConfiguration['nonCacheableAction']
            ]
        );
    }

    $GLOBALS['TYPO3_CONF_VARS']['LOG']['Vd']['VdSafarinet']['writerConfiguration'] = [
        \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
            \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                'logFile' => getenv('DGNSI_LOG_PATH') . '/siel/' . date('Y-m-d') . '.log'
            ]
        ]
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['vd_safarinet'] = [
        'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'groups' => [
            'pages'
        ],
        'options' => [
            'compression' => true,
            'defaultLifetime' => 900
        ]
    ];
})();
