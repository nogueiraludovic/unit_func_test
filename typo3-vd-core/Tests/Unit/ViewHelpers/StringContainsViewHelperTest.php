<?php

declare(strict_types=1);

namespace Vd\VdCore\ViewHelpers;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

final class StringContainsViewHelperTest extends TestCase
{
    protected RenderingContextInterface $renderingContext;

    #[Test]
    public function verdictReturnsTrueForSingleNeedle(): void
    {
        $this->assertTrue(
            StringContainsViewHelper::verdict(
                [
                    'caseSensitive' => false,
                    'haystack' => 'Hello World',
                    'needle' => 'World'
                ],
                $this->renderingContext
            )
        );
    }

    #[Test]
    public function verdictReturnsTrueForArrayNeedle(): void
    {
        $this->assertTrue(
            StringContainsViewHelper::verdict(
                [
                    'caseSensitive' => false,
                    'haystack' => 'Hello World',
                    'needle' => ['Foo', 'World']
                ],
                $this->renderingContext
            )
        );
    }

    #[Test]
    public function verdictReturnsFalseWhenNoNeedleMatches(): void
    {
        $this->assertFalse(
            StringContainsViewHelper::verdict(
                [
                    'caseSensitive' => false,
                    'haystack' => 'Hello World',
                    'needle' => ['Foo', 'Bar']
                ],
                $this->renderingContext
            )
        );
    }

    #[Test]
    public function verdictRespectsCaseSensitivity(): void
    {
        $this->assertFalse(
            StringContainsViewHelper::verdict(
                [
                    'caseSensitive' => true,
                    'haystack' => 'Hello World',
                    'needle' => 'world'
                ],
                $this->renderingContext
            )
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->renderingContext = $this->createMock(RenderingContextInterface::class);
    }
}
