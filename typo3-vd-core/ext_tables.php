<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Core\Environment;

defined('TYPO3') === true || die;

$stylesheetDirectory = match ((string)Environment::getContext()) {
    'Development/DDEV', 'Development/Podman', 'Production/DDEV', 'Production/Podman' =>
        'EXT:vd_core/Resources/Public/Css/Backend/Ddev',
    'Development/I2', 'Development/Integration' => 'EXT:vd_core/Resources/Public/Css/Backend/Int',
    'Production/Formation', 'Production/Validation' => 'EXT:vd_core/Resources/Public/Css/Backend/Val',
    default => ''
};

if ($stylesheetDirectory !== '') {
    $GLOBALS['TBE_STYLES']['skins']['vd_core']['stylesheetDirectories']['css'] = $stylesheetDirectory;
}
