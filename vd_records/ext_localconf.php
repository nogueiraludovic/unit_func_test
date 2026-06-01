<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_records/Configuration/TsConfig/Page/*.tsconfig\'
    ');

    \Vd\VdFrontend\Utility\FlexFormUtility::registerTable(
        'tt_address',
        [
            'address',
            'city',
            'full_address',
            'name',
            'tx_vdaddrgeneral_categoryid1',
            'tx_vdaddrgeneral_categoryid2',
            'tx_vdaddrgeneral_categoryid3',
            'tx_vdttaddressextprofilmonichien_profil',
            'zip'
        ],
        [
            'birthday'
        ],
        [
            'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:dog_trainers' => 'DogTrainers',
            'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:domestic_violence_organizations_directory' =>
                'DomesticViolenceOrganizationsDirectory',
            'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:security_cameras' => 'SecurityCameras'
        ]
    );

    \Vd\VdFrontend\Utility\FlexFormUtility::registerTable(
        'tx_vdfilesdb_file',
        [
            'categoryid',
            'categoryid2',
            'categoryid3',
            'categoryid4',
            'description',
            'no',
            'title',
            'vdmunicipalityid'
        ],
        [
            'datedocument',
            'sorting',
            'title'
        ],
        [
            'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:apprenticeships' => 'Apprenticeships',
            'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:car_forms' => 'CarForms',
            'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:courses_list' => 'CoursesList',
            'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:energy_forms' => 'EnergyForms',
            'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:navigation_forms' => 'NavigationForms',
            'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:regional_projects' => 'RegionalProjects',
            'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:slots_list' => 'SlotsList'
        ]
    );

    \Vd\VdFrontend\Utility\FlexFormUtility::registerTable(
        'tx_vdfilesdbsecri_file',
        [
            'categoryid',
            'datedocument',
            'description',
            'title'
        ],
        [
            'datedocument'
        ],
        [
            'LLL:EXT:vd_records/Resources/Private/Language/locallang_db.xlf:mails_list' => 'MailsList'
        ]
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['vdRecordsFullAddressUpdate'] =
        \Vd\VdRecords\Updates\FullAddressUpdate::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
        \Vd\VdRecords\Hooks\DataHandlerHook::class;
})();
