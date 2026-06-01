<?php

defined('TYPO3') === true || die;

(static function (): void {
    $GLOBALS['TCA']['sys_file_metadata']['columns']['alternative']['config']['eval'] = 'trim,required';
})();
