<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Utility;

use Exception;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ConfigurationUtility
{
    public static function getConfigurationOption(string $key, $defaultValue)
    {
        try {
            $value = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('vd_prestations', $key);
        } catch (Exception $_) {
            $value = $defaultValue;
        }

        return $value;
    }
}
