<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['Indexer']['indexPageSubstitutePageDocument'][\Vd\VdSolr\Hooks\ModifyPageTitleHook::class] =
        \Vd\VdSolr\Hooks\ModifyPageTitleHook::class;
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['modifySearchQuery'][\Vd\VdSolr\Hooks\ModifySearchQueryHook::class] =
        \Vd\VdSolr\Hooks\ModifySearchQueryHook::class;

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools::class]['flexParsing'][] =
        \Vd\VdSolr\Hooks\FlexFormToolsHook::class;

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['fr']['EXT:solr/Resources/Private/Language/locallang.xlf'][] =
        'EXT:vd_solr/Resources/Private/Language/fr.locallang.xlf';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\ApacheSolrForTypo3\Solr\System\Service\ConfigurationService::class]['className'] =
        \Vd\VdSolr\XClasses\ConfigurationServiceXClass::class;

    // @extensionScannerIgnoreLine
    $dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
    $dispatcher->connect(
        \ApacheSolrForTypo3\Solr\Domain\Index\IndexService::class,
        'beforeIndexItem',
        \Vd\VdSolr\Slot\IndexServiceSlot::class,
        'beforeIndexItemSlot'
    );
    $dispatcher->connect(
        \ApacheSolrForTypo3\Solr\Controller\SearchController::class,
        'beforeSearch',
        \Vd\VdSolr\Slot\SearchControllerSlot::class,
        'beforeSearchSlot'
    );
    $dispatcher->connect(
        \ApacheSolrForTypo3\Solr\Controller\SearchController::class,
        'resultsAction',
        \Vd\VdSolr\Slot\SearchControllerSlot::class,
        'manipulateValues',
        false
    );
})();
