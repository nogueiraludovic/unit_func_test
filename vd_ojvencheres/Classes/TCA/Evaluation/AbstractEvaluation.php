<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\TCA\Evaluation;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function preg_match;

abstract class AbstractEvaluation
{
    protected FlashMessageService $flashMessageService;

    public function __construct(FlashMessageService $flashMessageService)
    {
        $this->flashMessageService = $flashMessageService;
    }

    abstract public function evaluateFieldValue(string $value, string $_, bool &$set): string;

    public function returnFieldJS(): string
    {
        return 'return value;';
    }

    protected function evaluate4DigitsFieldValue(string $value, bool &$set, string $label): string
    {
        if ($value === '' || $value === '0') {
            return $value;
        }

        if ((bool)preg_match('/^\d{4}+$/', $value) === true) {
            return $value;
        }

        $set = false;

        $this->flashMessageService
            ->getMessageQueueByIdentifier()
            ->enqueue(
                GeneralUtility::makeInstance(
                    FlashMessage::class,
                    $this
                        ->getLanguageService()
                        ->sL('LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:invalid_' . $label),
                    '',
                    AbstractMessage::ERROR,
                    true
                )
            );

        return $value;
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
