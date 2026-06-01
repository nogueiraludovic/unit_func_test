<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3') === true || die;

(static function (): void {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        @import \'EXT:vd_publications_cg/Configuration/TsConfig/Page/Mod/web_list.tsconfig\'
        @import \'EXT:vd_publications_cg/Configuration/TsConfig/Page/TceForm/tx_vdpublicationscg_domain_model_document.tsconfig\'
    ');

    \Vd\VdFrontend\Utility\FlexFormUtility::registerTable(
        'tx_vdpublicationscg_domain_model_document',
        [
            'number',
            'text'
        ],
        [
            'publication_date'
        ]
    );
})();
