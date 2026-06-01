<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'powermail_pi1',
        'FILE:EXT:vd_powermail/Configuration/FlexForms/FlexformPi1.xml'
    );
})();
