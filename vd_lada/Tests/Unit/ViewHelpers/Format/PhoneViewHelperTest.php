<?php

declare(strict_types=1);

namespace Vd\VdLada\Tests\Unit\ViewHelpers\Format;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use Vd\VdLada\ViewHelpers\Format\PhoneViewHelper;

final class PhoneViewHelperTest extends UnitTestCase
{
    public function testRenderStaticWithValidPhoneNumber(): void
    {
        $phoneNumber = '+1 (555) 123-4567';
        $expectedHref = 'tel:+15551234567';

        $result = PhoneViewHelper::renderStatic(
            ['phone' => $phoneNumber, 'identifier' => 'abcd123'],
            static fn () => $phoneNumber,
            $this->createMock(RenderingContextInterface::class)
        );

        $this->assertStringContainsString($expectedHref, $result);
        $this->assertStringContainsString('<a class="phone-link" href="tel:', $result);
    }

    public function testRenderStaticWithPhoneNumberContainingSpaces(): void
    {
        $phoneNumber = '555 123 4567';
        $expectedHref = 'tel:5551234567';

        $result = PhoneViewHelper::renderStatic(
            ['phone' => $phoneNumber, 'identifier' => 'abcd123'],
            static fn () => $phoneNumber,
            $this->createMock(RenderingContextInterface::class)
        );

        $this->assertStringContainsString($expectedHref, $result);
    }

    public function testRenderStaticWithPhoneNumberContainingDashes(): void
    {
        $phoneNumber = '555-123-4567';
        $expectedHref = 'tel:5551234567';

        $result = PhoneViewHelper::renderStatic(
            ['phone' => $phoneNumber, 'identifier' => 'abcd123'],
            static fn () => $phoneNumber,
            $this->createMock(RenderingContextInterface::class)
        );

        $this->assertStringContainsString($expectedHref, $result);
    }

    public function testRenderStaticWithPhoneNumberContainingLetters(): void
    {
        $phoneNumber = 'Call: 555-ABC-7890';
        $expectedHref = 'tel:5557890';

        $result = PhoneViewHelper::renderStatic(
            ['phone' => $phoneNumber, 'identifier' => 'abcd123'],
            static fn () => $phoneNumber,
            $this->createMock(RenderingContextInterface::class)
        );

        $this->assertStringContainsString($expectedHref, $result);
    }

    public function testRenderStaticWithEmptyPhoneNumber(): void
    {
        $phoneNumber = '';
        $expectedHref = 'tel:';

        $result = PhoneViewHelper::renderStatic(
            ['phone' => $phoneNumber, 'identifier' => 'abcd123'],
            static fn () => $phoneNumber,
            $this->createMock(RenderingContextInterface::class)
        );

        $this->assertStringContainsString($expectedHref, $result);
    }
}
