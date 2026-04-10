<?php

declare(strict_types=1);

namespace Vd\VdCore\Hooks;

/** @noinspection PhpDeprecationInspection */
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\InputButton;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;

readonly class ButtonsHook
{
    public function __construct(protected IconFactory $iconFactory)
    {
    }

    public function addSaveCloseButton(array $parameters, ButtonBar $buttonBar): array
    {
        $buttons = $parameters['buttons'];
        $saveButton = $buttons[ButtonBar::BUTTON_POSITION_LEFT][2][0] ?? null;

        if (($saveButton instanceof InputButton) === true) {
            $buttons[ButtonBar::BUTTON_POSITION_LEFT][2][] = $buttonBar
                ->makeInputButton()
                ->setForm($saveButton->getForm())
                ->setIcon($this->iconFactory->getIcon('actions-document-save-close', Icon::SIZE_SMALL))
                ->setName('_saveandclosedok')
                ->setShowLabelText(true)
                ->setTitle(
                    $this
                        ->getLanguageService()
                        ->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:rm.saveCloseDoc')
                )
                ->setValue('1');
        }

        return $buttons;
    }

    #[CodeCoverageIgnore]
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
