<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tt_address',
        [
            'parent' => [
                'config' => [
                    'default' => 0,
                    'type' => 'passthrough'
                ]
            ]
        ]
    );

    $GLOBALS['TCA']['tt_address']['columns']['first_name']['config']['eval'] = 'trim,required';
    $GLOBALS['TCA']['tt_address']['columns']['last_name']['config']['eval'] = 'trim,required';
})();
