<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_contact_service/Configuration/TsConfig/Page/Mod/web_list.tsconfig\'
        @import \'EXT:vd_contact_service/Configuration/TsConfig/Page/Mod/Wizards/NewContentElement.tsconfig\'
        @import \'EXT:vd_contact_service/Configuration/TsConfig/Page/TceMain/linkHandler.tsconfig\'
    ');

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdContactService',
        'Search',
        [
            \Vd\VdContactService\Controller\ServiceContactController::class => 'search'
        ],
        [
            \Vd\VdContactService\Controller\ServiceContactController::class => 'search'
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'VdContactService',
        'Contact',
        [
            \Vd\VdContactService\Controller\ServiceContactController::class => 'show'
        ]
    );

    if ($GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] === '') {
        $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] =
            'backend_layout,service_contact,service_contact_hidden,service_contact_hidden_subpages';
    } else {
        $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] .=
            ',backend_layout,service_contact,service_contact_hidden,service_contact_hidden_subpages';
    }

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList::class]['makeSearchStringConstraints'][] =
        \Vd\VdContactService\Hooks\DatabaseRecordListHook::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][\TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList::class]['modifyQuery'][] =
        \Vd\VdContactService\Hooks\DatabaseRecordListHook::class;

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1721890902] = [
        'class' => \Vd\VdContactService\TCA\Form\Element\ServiceContactElement::class,
        'nodeName' => 'serviceContact',
        'priority' => 40
    ];

    // @extensionScannerIgnoreLine
    $dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
    $dispatcher->connect(
        \In2code\Powermail\ViewHelpers\Misc\PrefillFieldViewHelper::class,
        'render',
        \Vd\VdContactService\Slot\PowermailSlot::class,
        'prefillFields',
        false
    );
    $dispatcher->connect(
        \In2code\Powermail\Domain\Service\Mail\SendMailService::class,
        'sendTemplateEmailBeforeSend',
        \Vd\VdContactService\Slot\PowermailSlot::class,
        'manipulateMail',
        false
    );
})();
