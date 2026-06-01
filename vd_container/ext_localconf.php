<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_container/Configuration/TsConfig/Page/TceForm/tt_content.tsconfig\'
    ');
})();
