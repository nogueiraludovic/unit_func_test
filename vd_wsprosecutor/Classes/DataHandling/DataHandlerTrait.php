<?php

declare(strict_types=1);

namespace Vd\VdWsprosecutor\DataHandling;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdCore\Authentication\AuthenticationTrait;
use Vd\VdWsprosecutor\Service\CacheService;

use function count;

trait DataHandlerTrait
{
    use AuthenticationTrait;

    protected function processDataHandling(array $cmd = [], array $data = []): void
    {
        $hooks = [];

        if (count($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']) > 0) {
            $hooks = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php'];

            unset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']);
        }

        if (isset($GLOBALS['LANG']) === false) {
            $GLOBALS['LANG'] = GeneralUtility::makeInstance(LanguageService::class);
        }

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start($data, $cmd, $this->getFakeAdminUser('vd-wsprosecutor'));

        if (count($cmd) > 0) {
            $dataHandler->process_cmdmap();
        }

        if (count($data) > 0) {
            $dataHandler->process_datamap();
        }

        GeneralUtility::makeInstance(CacheService::class)->flushCachesInPagesByTags();

        if (count($hooks) > 0) {
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php'] = $hooks;
        }
    }
}
