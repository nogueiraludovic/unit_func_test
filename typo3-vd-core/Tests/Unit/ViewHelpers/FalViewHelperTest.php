<?php

declare(strict_types=1);

namespace Vd\VdCore\ViewHelpers;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;

final class FalViewHelperTest extends TestCase
{
    #[Test]
    public function renderAssignsItemsAndReturnsChildren(): void
    {
        $fileRepository = $this->createMock(FileRepository::class);
        $fileRepository->method('findByRelation')->willReturn(['file1']);

        GeneralUtility::setSingletonInstance(FileRepository::class, $fileRepository);

        $variableProvider = $this->createMock(VariableProviderInterface::class);
        $variableProvider->expects($this->once())->method('add')->with('items', ['file1']);
        $variableProvider->expects($this->once())->method('remove')->with('items');

        $renderingContext = $this->createMock(RenderingContextInterface::class);
        $renderingContext->method('getVariableProvider')->willReturn($variableProvider);

        $viewHelper = new FalViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'as' => 'items',
            'data' => ['uid' => 10],
            'field' => 'image',
            'table' => 'tt_content'
        ]);

        $viewHelper->setRenderChildrenClosure(static fn () => 'content');

        $this->assertSame('content', $viewHelper->render());
    }

    #[Test]
    public function renderAssignsNullWhenUidIsInvalid(): void
    {
        $variableProvider = $this->createMock(VariableProviderInterface::class);
        $variableProvider->expects($this->once())->method('add')->with('items', null);
        $variableProvider->expects($this->once())->method('remove')->with('items');

        $renderingContext = $this->createMock(RenderingContextInterface::class);
        $renderingContext->method('getVariableProvider')->willReturn($variableProvider);

        $viewHelper = new FalViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'as' => 'items',
            'data' => ['uid' => 0],
            'field' => 'image',
            'table' => 'tt_content'
        ]);

        $viewHelper->setRenderChildrenClosure(static fn () => 'content');

        $this->assertSame('content', $viewHelper->render());
    }

    #[Test]
    public function renderUsesLocalizedUidWhenAvailable(): void
    {
        $fileRepository = $this->createMock(FileRepository::class);
        $fileRepository->expects($this->exactly(2))->method('findByRelation')->willReturnOnConsecutiveCalls(
            ['original'],
            ['localized']
        );

        GeneralUtility::setSingletonInstance(FileRepository::class, $fileRepository);

        $GLOBALS['TCA']['tt_content']['ctrl']['languageField'] = 'sys_language_uid';
        $GLOBALS['TCA']['tt_content']['ctrl']['transOrigPointerField'] = 'l10n_parent';

        $variableProvider = $this->createMock(VariableProviderInterface::class);
        $variableProvider->expects($this->once())->method('add')->with('items', ['localized']);
        $variableProvider->expects($this->once())->method('remove')->with('items');

        $renderingContext = $this->createMock(RenderingContextInterface::class);
        $renderingContext->method('getVariableProvider')->willReturn($variableProvider);

        $viewHelper = new FalViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'as' => 'items',
            'data' => ['uid' => 10, '_LOCALIZED_UID' => 99],
            'field' => 'image',
            'table' => 'tt_content'
        ]);

        $viewHelper->setRenderChildrenClosure(static fn () => 'content');

        $this->assertSame('content', $viewHelper->render());
    }

    #[Test]
    public function renderUsesPagesOverlayUidWhenLocalizedUidIsMissing(): void
    {
        $fileRepository = $this->createMock(FileRepository::class);
        $fileRepository->expects($this->exactly(2))->method('findByRelation')->willReturnOnConsecutiveCalls(
            ['original'],
            ['overlay']
        );

        GeneralUtility::setSingletonInstance(FileRepository::class, $fileRepository);

        $GLOBALS['TCA']['tt_content']['ctrl']['languageField'] = 'sys_language_uid';
        $GLOBALS['TCA']['tt_content']['ctrl']['transOrigPointerField'] = 'l10n_parent';

        $variableProvider = $this->createMock(VariableProviderInterface::class);
        $variableProvider->expects($this->once())->method('add')->with('items', ['overlay']);
        $variableProvider->expects($this->once())->method('remove')->with('items');

        $renderingContext = $this->createMock(RenderingContextInterface::class);
        $renderingContext->method('getVariableProvider')->willReturn($variableProvider);

        $viewHelper = new FalViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'as' => 'items',
            'data' => ['uid' => 10, '_PAGES_OVERLAY_UID' => 77],
            'field' => 'image',
            'table' => 'tt_content'
        ]);

        $viewHelper->setRenderChildrenClosure(static fn () => 'content');

        $this->assertSame('content', $viewHelper->render());
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();

        parent::tearDown();
    }
}
