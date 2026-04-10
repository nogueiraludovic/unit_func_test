<?php

declare(strict_types=1);

namespace Vd\VdCore\Hooks;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Backend\Controller\PageLayoutController;
use TYPO3\CMS\Backend\Module\ModuleLoader;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class PageLayoutControllerHookTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = [
        'backend'
    ];

    #[Test]
    public function renderReturnsScriptForSysfolder(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/PageLayoutControllerHookTest.csv');

        $moduleLoader = $this->getMockBuilder(ModuleLoader::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['load'])
            ->getMock();

        $moduleLoader->modules['web']['sub']['list'] = [];

        $controller = $this->getMockBuilder(PageLayoutController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $controller->id = 100;

        $result = (new PageLayoutControllerHook($moduleLoader))->render([], $controller);

        $this->assertSame(
            '<script>top.TYPO3.ModuleMenu.App.showModule("web_list", true);</script>',
            $result
        );
    }

    #[Test]
    public function renderReturnsScriptForSysfolderWithoutModule(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/PageLayoutControllerHookTest.csv');

        $moduleLoader = $this->getMockBuilder(ModuleLoader::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['load'])
            ->getMock();

        $moduleLoader->modules['web']['sub']['list'] = null;

        $controller = $this->getMockBuilder(PageLayoutController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $controller->id = 100;

        $result = (new PageLayoutControllerHook($moduleLoader))->render([], $controller);

        $this->assertSame(
            '',
            $result
        );
    }

    #[Test]
    public function renderReturnsEmptyStringForNonSysfolder(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/PageLayoutControllerHookTest.csv');

        $moduleLoader = $this->getMockBuilder(ModuleLoader::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['load'])
            ->getMock();

        $moduleLoader->modules['web']['sub']['list'] = [];

        $controller = $this->getMockBuilder(PageLayoutController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $controller->id = 200;

        $result = (new PageLayoutControllerHook($moduleLoader))->render([], $controller);

        $this->assertSame('', $result);
    }
}
