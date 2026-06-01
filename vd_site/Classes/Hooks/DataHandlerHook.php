<?php

declare(strict_types=1);

namespace Vd\VdSite\Hooks;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function strpos;

class DataHandlerHook
{
    public function processDatamap_preProcessFieldArray(array $fields, string $table): void
    {
        foreach ($fields as $fieldName => $fieldValue) {
            if (
                $GLOBALS['TCA'][$table]['columns'][$fieldName]['config']['type'] !== 'input'
                && $GLOBALS['TCA'][$table]['columns'][$fieldName]['config']['type'] !== 'text'
            ) {
                continue;
            }

            $fieldValue = (string)$fieldValue;

            if (
                strpos($fieldValue, 'http://vd.ch') === false
                && strpos($fieldValue, 'http://www.vd.ch') === false
                && strpos($fieldValue, 'https://vd.ch') === false
                && strpos($fieldValue, 'https://www.vd.ch') === false
            ) {
                continue;
            }

            GeneralUtility::makeInstance(FlashMessageService::class)
                ->getMessageQueueByIdentifier()
                ->enqueue(
                    GeneralUtility::makeInstance(
                        FlashMessage::class,
                        $this->getMessage($fieldName, $table),
                        '',
                        FlashMessage::WARNING,
                        true
                    )
                );
        }
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    protected function getMessage(string $field, string $table): string
    {
        return 'Pour éviter les liens cassés, merci de faire le lien en utilisant l’arborescence TYPO3 et non un lien externe (champs : "'
            . $this->getLanguageService()->sl($GLOBALS['TCA'][$table]['columns'][$field]['label'])
            . '").';
    }
}
