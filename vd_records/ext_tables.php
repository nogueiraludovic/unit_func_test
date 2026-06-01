<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_vdlisteprofessionsdb_domain');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_vdlisteprofessionsdb_job');
})();
