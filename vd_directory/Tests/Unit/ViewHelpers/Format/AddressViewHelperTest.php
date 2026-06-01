<?php

declare(strict_types=1);

namespace Vd\VdDirectory\Tests\Unit\ViewHelpers\Format;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use Vd\VdDirectory\ViewHelpers\Format\AddressViewHelper;

final class AddressViewHelperTest extends UnitTestCase
{
    public function testRenderStaticFormatsAddressCorrectly(): void
    {
        $testCases = [
            [
                'input' => [
                    'address' => "123 Main Street\nApt 4B",
                    'zip' => '10001',
                    'city' => 'New York'
                ],
                'expected' => '<ul class="list-unstyled">'
                    . "<li>123 Main Street<br />\nApt 4B</li>"
                    . '<li>10001 New York</li>'
                    . '</ul>'
            ],
            [
                'input' => [
                    'address' => '456 Elm St',
                    'zip' => '',
                    'city' => ''
                ],
                'expected' => '<ul class="list-unstyled">'
                    . '<li>456 Elm St</li>'
                    . '</ul>'
            ],
            [
                'input' => [
                    'address' => '',
                    'zip' => '90210',
                    'city' => 'Beverly Hills'
                ],
                'expected' => '<ul class="list-unstyled">'
                    . '<li>90210 Beverly Hills</li>'
                    . '</ul>'
            ],
            [
                'input' => [
                    'address' => '',
                    'zip' => '',
                    'city' => ''
                ],
                'expected' => '<ul class="list-unstyled"></ul>'
            ]
        ];

        foreach ($testCases as $testCase) {
            $this->assertSame(
                $testCase['expected'],
                AddressViewHelper::renderStatic(
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
