<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Vd\VdSmallAds\Controller\SmallAdsController;

defined('TYPO3') === true || die;

ExtensionManagementUtility::addPageTSConfig('@import \'EXT:vd_small_ads/Configuration/page.tsconfig\'');

ExtensionUtility::configurePlugin(
    'VdSmallAds',
    'Pi1',
    [
        SmallAdsController::class => 'list,reset'
    ],
    [
        SmallAdsController::class => 'list,reset'
    ]
);
