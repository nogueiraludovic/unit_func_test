<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_wsprosecutor/Configuration/TsConfig/Page/Mod/web_list.tsconfig\'
        @import \'EXT:vd_wsprosecutor/Configuration/TsConfig/Page/Mod/Wizards/NewContentElement.tsconfig\'
    ');

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdWsprosecutor',
        'OfficeHourCurrent',
        [
            \Vd\VdWsprosecutor\Controller\OfficeHourController::class => 'current'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdWsprosecutor',
        'OfficeHourList',
        [
            \Vd\VdWsprosecutor\Controller\OfficeHourController::class => 'list'
        ]
    );

    $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['vd_wsprosecutor'] =
        \Vd\VdWsprosecutor\Controller\EsbController::class;

    $GLOBALS['TYPO3_CONF_VARS']['LOG']['Vd']['VdWsprosecutor']['writerConfiguration'] = [
        \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
            \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                'logFile' => getenv('DGNSI_LOG_PATH') . '/prosecutors/' . date('Y-m-d') . '.log'
            ]
        ]
    ];
})();
