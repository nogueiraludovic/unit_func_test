<?php

declare(strict_types=1);

namespace Vd\VdDirectory\Tests\Unit\ViewHelpers\Format;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use Vd\VdDirectory\ViewHelpers\Format\PhonesViewHelper;

final class PhonesViewHelperTest extends UnitTestCase
{
    public function testRenderStaticFormatsPhonesCorrectly(): void
    {
        $testCases = [
            [
                'input' => [
                    ['phone' => '079 123 45 67'],
                    ['phone' => '+41 44 123 45 67']
                ],
                'expected' => '<ul class="list-unstyled">'
                    . '<li><a href="tel:0791234567" id="phone-link-0" rel="nofollow">079 123 45 67</a></li>'
                    . '<li><a href="tel:+41441234567" id="phone-link-1" rel="nofollow">+41 44 123 45 67</a></li>'
                    . '</ul>'
            ],
            [
                'input' => [
                    ['phone' => '123.456.7890'],
                ],
                'expected' => '<ul class="list-unstyled">'
                    . '<li><a href="tel:1234567890" id="phone-link-0" rel="nofollow">123.456.7890</a></li>'
                    . '</ul>'
            ],
            [
                'input' => [],
                'expected' => '<ul class="list-unstyled"></ul>'
            ]
        ];

        foreach ($testCases as $testCase) {
            $this->assertSame(
                $testCase['expected'],
                PhonesViewHelper::renderStatic(
                    [],
                    static function () use ($testCase) {
                        return $testCase['input'];
                    },
                    $this->createMock(RenderingContextInterface::class)
                )
            );
        }
    }
}
