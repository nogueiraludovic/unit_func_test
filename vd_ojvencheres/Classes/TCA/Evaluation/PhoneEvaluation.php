<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\TCA\Evaluation;

use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function preg_match;
use function str_replace;
use function substr;

class PhoneEvaluation extends AbstractEvaluation
{
    public function evaluateFieldValue(string $value, string $_, bool &$set): string
    {
        if ($value === '' || $value === '0') {
            return $value;
        }

        $value = str_replace([' ', '/', '.'], '', $value);

        if ((bool)preg_match('/^[+](412)\d{8}$/', $value) === true) {
            return substr($value, 0, 3) . ' '
                . substr($value, 3, 2) . ' '
                . substr($value, 5, 3) . ' '
                . substr($value, 8, 2) . ' '
                . substr($value, 10, 2);
        }

        if ((bool)preg_match('/^[+](417)\d{8}$/', $value) === true) {
            return substr($value, 0, 3) . ' '
                . substr($value, 3, 2) . ' '
                . substr($value, 5, 3) . ' '
                . substr($value, 8, 2) . ' '
                . substr($value, 10, 2);
        }

        if ((bool)preg_match('/^(00412)\d{8}$/', $value) === true) {
            return '+41 ' . substr($value, 4, 2) . ' '
                . substr($value, 6, 3) . ' '
                . substr($value, 9, 2) . ' '
                . substr($value, 11, 2);
        }

        if ((bool)preg_match('/^(00417)\d{8}$/', $value) === true) {
            return '+41 ' . substr($value, 4, 2) . ' '
                . substr($value, 6, 3) . ' '
                . substr($value, 9, 2) . ' '
                . substr($value, 11, 2);
        }

        if ((bool)preg_match('/^(02)\d{8}$/', $value) === true) {
            return '+41 ' . substr($value, 1, 2) . ' '
                . substr($value, 3, 3) . ' '
                . substr($value, 6, 2) . ' '
                . substr($value, 8, 2);
        }

        if ((bool)preg_match('/^(07)\d{8}$/', $value) === true) {
            return '+41 ' . substr($value, 1, 2) . ' '
                . substr($value, 3, 3) . ' '
                . substr($value, 6, 2) . ' '
                . substr($value, 8, 2);
        }

        $set = false;

        $this->flashMessageService
            ->getMessageQueueByIdentifier()
            ->enqueue(
                GeneralUtility::makeInstance(
                    FlashMessage::class,
                    $this
                        ->getLanguageService()
                        ->sL('LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:invalid_phone'),
                    '',
                    AbstractMessage::ERROR,
                    true
                )
            );

        return $value;
    }
}
