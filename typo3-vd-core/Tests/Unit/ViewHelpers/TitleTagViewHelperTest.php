<?php

declare(strict_types=1);

namespace Vd\VdCore\ViewHelpers;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use Vd\VdCore\PageTitle\ViewHelperTitleProvider;

final class TitleTagViewHelperTest extends TestCase
{
    #[Test]
    public function renderDoesNothingWhenContentIsEmpty(): void
    {
        $provider = $this->createMock(ViewHelperTitleProvider::class);
        $provider->expects($this->never())->method('setTitle');

        GeneralUtility::setSingletonInstance(ViewHelperTitleProvider::class, $provider);

        $viewHelper = new TitleTagViewHelper();
        $viewHelper->setRenderingContext($this->createMock(RenderingContextInterface::class));
        $viewHelper->setArguments(['value' => '   ']);

        $viewHelper->render();
    }

    #[Test]
    public function renderSetsTitleWhenContentIsNotEmpty(): void
    {
        $provider = $this->createMock(ViewHelperTitleProvider::class);
        $provider->expects($this->once())->method('setTitle')->with('My Title');

        GeneralUtility::setSingletonInstance(ViewHelperTitleProvider::class, $provider);

        $viewHelper = new TitleTagViewHelper();
        $viewHelper->setRenderingContext($this->createMock(RenderingContextInterface::class));
        $viewHelper->setArguments(['value' => ' My Title ']);

        $viewHelper->render();
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
        parent::tearDown();
    }
}
