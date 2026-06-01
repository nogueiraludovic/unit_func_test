<?php

defined('TYPO3') === true || die;

(static function (): void {
    $GLOBALS['TCA']['sys_file_reference']['palettes']['pressReleaseOverlayPalette']['showitem'] =
        'title,alternative,--linebreak--,description';
})();
