<?php

declare(strict_types=1);

use Vd\VdCore\Hooks\BackendAlertHook;
use Vd\VdCore\Hooks\ButtonsHook;
use Vd\VdCore\Hooks\DataHandlerHook;
use Vd\VdCore\Hooks\PageLayoutControllerHook;
use Vd\VdCore\Routing\Aspect\StaticIdentifierMapper;

defined('TYPO3') === true || die;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook'][] =
    ButtonsHook::class . '->addSaveCloseButton';
// @extensionScannerIgnoreLine
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/db_layout.php']['drawFooterHook'][] =
    PageLayoutControllerHook::class . '->render';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/db_layout.php']['drawHeaderHook'][] =
    BackendAlertHook::class . '->drawHeader';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][] =
    DataHandlerHook::class . '->clearAdditionalCache';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
    DataHandlerHook::class;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['StaticIdentifierMapper'] = StaticIdentifierMapper::class;
