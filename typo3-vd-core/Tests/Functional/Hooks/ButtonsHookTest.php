<?php

declare(strict_types=1);

namespace Vd\VdCore\Hooks;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\InputButton;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class ButtonsHookTest extends FunctionalTestCase
{
    #[Test]
    public function addSaveCloseButtonAddsButtonWhenSaveButtonExists(): void
    {
        $iconFactory = $this->createMock(IconFactory::class);
        $iconFactory->method('getIcon')->willReturn($this->createMock(Icon::class));

        $saveButton = $this->createMock(InputButton::class);
        $saveButton->method('getForm')->willReturn('editform');

        $newButton = $this->createMock(InputButton::class);
        $newButton->method('setForm')->willReturnSelf();
        $newButton->method('setIcon')->willReturnSelf();
        $newButton->method('setName')->willReturnSelf();
        $newButton->method('setShowLabelText')->willReturnSelf();
        $newButton->method('setTitle')->willReturnSelf();
        $newButton->method('setValue')->willReturnSelf();

        $buttonBar = $this->createMock(ButtonBar::class);
        $buttonBar->method('makeInputButton')->willReturn($newButton);

        $GLOBALS['LANG'] = $this->createMock(LanguageService::class);
        $GLOBALS['LANG']->method('sL')->willReturn('Save and close');

        $parameters['buttons'][ButtonBar::BUTTON_POSITION_LEFT][2] = [$saveButton];

        $result = (new ButtonsHook($iconFactory))->addSaveCloseButton($parameters, $buttonBar);

        $this->assertCount(2, $result[ButtonBar::BUTTON_POSITION_LEFT][2]);
    }

    #[Test]
    public function addSaveCloseButtonDoesNothingWhenNoSaveButton(): void
    {
        $iconFactory = $this->createMock(IconFactory::class);
        $buttonBar = $this->createMock(ButtonBar::class);

        $parameters['buttons'][ButtonBar::BUTTON_POSITION_LEFT][2] = [];

        $result = (new ButtonsHook($iconFactory))->addSaveCloseButton($parameters, $buttonBar);

        $this->assertSame($parameters['buttons'], $result);
    }
}
