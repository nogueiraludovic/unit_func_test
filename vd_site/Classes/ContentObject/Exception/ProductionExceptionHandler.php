<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdSite\ContentObject\Exception;

use Exception;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;
use TYPO3\CMS\Frontend\ContentObject\Exception\ProductionExceptionHandler as DefaultProductionExceptionHandler;

use function array_map;
use function date;
use function in_array;
use function sprintf;

class ProductionExceptionHandler extends DefaultProductionExceptionHandler
{
    public function handle(
        Exception $exception,
        AbstractContentObject $contentObject = null,
        $contentObjectConfiguration = []
    ): string {
        // ImmediateResponseException should work similar to exit / die and must therefore not be handled by this ExceptionHandler.
        if (($exception instanceof ImmediateResponseException) === true) {
            throw $exception;
        }

        if (
            empty($this->configuration['ignoreCodes.']) === false
            && in_array($exception->getCode(), array_map('intval', $this->configuration['ignoreCodes.']), true) === true
        ) {
            throw $exception;
        }

        $GLOBALS['TSFE']->set_no_cache('There is an error in the current page', true);

        $errorMessageInLog = 'Oops, an error occurred! Code: %s';
        $code = date('YmdHis', $_SERVER['REQUEST_TIME'])
            . GeneralUtility::makeInstance(Random::class)->generateRandomHexString(8);

        $this->logException($exception, $errorMessageInLog, $code);

        return sprintf(($this->configuration['errorMessage'] ?? $errorMessageInLog), $code);
    }
}
