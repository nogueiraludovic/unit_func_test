<?php

declare(strict_types=1);

namespace Vd\VdLada\Tests\Unit\ViewHelpers\Format;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use Vd\VdLada\ViewHelpers\Format\GoogleMapsViewHelper;

final class GoogleMapsViewHelperTest extends UnitTestCase
{
    public function testRenderStaticWithCompleteAddress(): void
    {
        $record = [
            'address' => '123 Main Street',
            'zip' => '12345',
            'city' => [['name' => 'TestCity']]
        ];

        $expectedUrl = 'https://www.google.com/maps/place/123+Main+Street,12345+TestCity';

        $result = GoogleMapsViewHelper::renderStatic(
            ['record' => $record],
            static fn () => $record,
            $this->createMock(RenderingContextInterface::class)
        );

        $this->assertStringContainsString($expectedUrl, $result);
        $this->assertStringContainsString('<ul class="list-unstyled mb-0">', $result);
    }

    public function testRenderStaticWithEmptyAddress(): void
    {
        $record = [
            'address' => '',
            'zip' => '12345',
            'city' => [['name' => 'TestCity']]
        ];

        $expectedUrl = 'https://www.google.com/maps/place/12345+TestCity';

        $result = GoogleMapsViewHelper::renderStatic(
            ['record' => $record],
            static fn () => $record,
            $this->createMock(RenderingContextInterface::class)
        );

        $this->assertStringContainsString($expectedUrl, $result);
    }

    public function testRenderStaticWithNoCity(): void
    {
        $record = [
            'address' => '123 Main Street',
            'zip' => '12345',
            'city' => []
        ];

        $expectedUrl = 'https://www.google.com/maps/place/123+Main+Street,12345';

        $result = GoogleMapsViewHelper::renderStatic(
            ['record' => $record],
            static fn () => $record,
            $this->createMock(RenderingContextInterface::class)
        );

        $this->assertStringContainsString($expectedUrl, $result);
    }

    public function testRenderStaticWithEmptyRecord(): void
    {
        $record = [
            'address' => '',
            'zip' => '',
            'city' => []
        ];

        $result = GoogleMapsViewHelper::renderStatic(
            ['record' => $record],
            static fn () => $record,
            $this->createMock(RenderingContextInterface::class)
        );

        $this->assertStringContainsString('<a href="https://www.google.com/maps/place/"', $result);
    }
}
