<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'pages',
        [
            'solr_boost' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'type' => 'text'
                ],
                'exclude' => true,
                'label' => 'Boost'
            ]
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', '--div--;Solr,solr_boost');
})();
