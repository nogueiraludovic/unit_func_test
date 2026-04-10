<?php

declare(strict_types=1);

namespace Vd\VdCore\DataProcessing;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\PageTitle\PageTitleProviderManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class PageTitleProcessorTest extends FunctionalTestCase
{
    #[Test]
    public function processAddsPageTitle(): void
    {
        $manager = $this->createMock(PageTitleProviderManager::class);
        $manager->method('getTitle')->willReturn('My Title');

        $cObj = new ContentObjectRenderer();
        $cObj->start([]);

        $result = (new PageTitleProcessor($manager))->process(
            $cObj,
            [],
            [],
            []
        );

        $this->assertSame('My Title', $result['pageTitle']);
    }

    #[Test]
    public function processSkipsEmptyTitle(): void
    {
        $manager = $this->createMock(PageTitleProviderManager::class);
        $manager->method('getTitle')->willReturn('');

        $cObj = new ContentObjectRenderer();
        $cObj->start([]);

        $result = (new PageTitleProcessor($manager))->process(
            $cObj,
            [],
            [],
            ['existing' => 'value']
        );

        $this->assertSame(['existing' => 'value'], $result);
    }
}
