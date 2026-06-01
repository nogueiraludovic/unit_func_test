<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
        [
            'LLL:EXT:vd_sqli_calculetteaci/Resources/Private/Language/locallang_db.xlf:tt_content.list_type_pi1',
            'vd_sqli_calculetteaci_pi1'
        ],
        'list_type',
        'vd_sqli_calculetteaci'
    );
})();
