<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\FormDataProvider;

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Localization\LanguageService;

use function time;

class CustomProvider implements FormDataProviderInterface
{
    protected const PRESS_RELEASE_RELAY_TYPE = 7;

    public function addData(array $result): array
    {
        $table = $result['tableName'];

        if ($table !== 'tx_vdpressreleases_domain_model_pressrelease') {
            return $result;
        }

        $type = (int)$result['databaseRow']['type'][0];

        if ($type === self::PRESS_RELEASE_RELAY_TYPE) {
            $result['processedTca']['columns']['forcedpdf_file']['config']['minitems'] = 1;
            $result['processedTca']['columns']['forcedpdf_file']['label'] =
                $this->getLanguageService()->sL(
                    'LLL:EXT:vd_pressreleases/Resources/Private/Language/locallang_db.xlf:files_relay'
                );
        }

        if ($type > 0 && (int)$result['databaseRow']['date_time'] === 0) {
            $result['databaseRow']['date_time'] = time();
        }

        return $result;
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
