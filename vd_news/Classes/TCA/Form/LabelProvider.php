<?php

declare(strict_types=1);

namespace Vd\VdNews\TCA\Form;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Localization\LanguageService;

use function sprintf;

class LabelProvider
{
    public function forNews(array &$parameters): void
    {
        $parameters['title'] = $parameters['row'][$GLOBALS['TCA'][$parameters['table']]['ctrl']['label']];

        if ((int)$parameters['row']['type'] !== 3) {
            return;
        }

        $record = BackendUtility::getRecord($parameters['table'], $parameters['row']['selected_article'], 'title');

        if ($record['title'] === '') {
            return;
        }

        $parameters['title'] = sprintf(
            $this->getLanguageService()->sL('LLL:EXT:vd_news/Resources/Private/Language/locallang_be.xlf:alias_of'),
            $record['title']
        );
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
