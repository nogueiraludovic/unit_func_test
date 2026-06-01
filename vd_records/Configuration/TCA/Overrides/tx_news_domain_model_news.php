<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tx_news_domain_model_news',
        [
            'event_end_date' => [
                'config' => [
                    'default' => 0,
                    'eval' => 'datetime',
                    'renderType' => 'inputDateTime',
                    'size' => 10,
                    'type' => 'input'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:end_date_of_event'
            ],
            'vd_display_date_in_title' => [
                'config' => [
                    'default' => true,
                    'renderType' => 'checkboxToggle',
                    'type' => 'check'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:display_date_in_title'
            ]
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'tx_news_domain_model_news',
        '
            --div--;LLL:EXT:vd_records/Resources/Private/Language/Form/locallang_tabs.xlf:agenda_events,
                vd_display_date_in_title,
                event_end_date
        '
    );
})();
