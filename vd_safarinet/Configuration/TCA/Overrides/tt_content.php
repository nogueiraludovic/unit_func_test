<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    $plugins = [
        'DecisionShow' => 'decision_show',
        'GcList' => 'gc_list',
        'GcMeetingShow' => 'gc_meeting_show',
        'GcPointShow' => 'gc_point_show',
        'GroupShow' => 'group_show',
        'MeetingList' => 'meeting_list',
        'MeetingShow' => 'meeting_show',
        'MemberDistrict' => 'member_district',
        'MemberList' => 'member_list',
        'MemberParty' => 'member_party',
        'MemberShow' => 'member_show',
        'ObjectShow' => 'object_show'
    ];

    foreach ($plugins as $pluginName => $pluginLabel) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'VdSafarinet',
            $pluginName,
            'LLL:EXT:vd_safarinet/Resources/Private/Language/locallang_plugins.xlf:' . $pluginLabel,
            null,
            'Safarinet'
        );
    }

    $pluginsSignature = [
        'vdsafarinet_decisionshow',
        'vdsafarinet_gclist',
        'vdsafarinet_gcmeetingshow',
        'vdsafarinet_gcpointshow',
        'vdsafarinet_groupshow',
        'vdsafarinet_meetinglist',
        'vdsafarinet_meetingshow',
        'vdsafarinet_memberdistrict',
        'vdsafarinet_memberlist',
        'vdsafarinet_memberparty',
        'vdsafarinet_membershow',
        'vdsafarinet_objectshow'
    ];

    foreach ($pluginsSignature as $pluginSignature) {
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] =
            'layout,pages,recursive';
    }
})();
