<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43(
        'vd_sqli_calculetteaci',
        'Classes/Plugin/AciCalculatorPlugin.php',
        '_pi1'
    );
})();
