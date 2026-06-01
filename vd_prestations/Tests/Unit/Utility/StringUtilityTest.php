<?php

namespace Vd\VdPrestations\Tests\Unit\Utility;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdPrestations\Utility\StringUtility;

final class StringUtilityTest extends UnitTestCase
{
    public function generateUniqueIdDataProvider(): array
    {
        return [
            [
                'test ' . "\n" . 'input ',
                '5eed650258ee'
            ],
            [
                'another input',
                'ab251f6cc209'
            ],
            [
                ' input with spaces ',
                'ba3c37743ef4'
            ],
            [
                ' consistent' . "\r\n" . ' input ' . "\n" . ' ',
                '1787a15641b1'
            ],
            [
                '',
                'd41d8cd98f00'
            ]
        ];
    }

    public function generateUniqueIdFailureDataProvider(): array
    {
        return [
            [
                'test ' . "\n" . 'input ',
                '5eed650258ee02f6a77c87b748b764ec'
            ],
            [
                'another input',
                'ab251f6cc20937359c93fc5ae06b22d1'
            ],
            [
                ' input with spaces ',
                'ba3c37743ef43586422a1eb46ac6aeb1'
            ],
            [
                ' consistent' . "\r\n" . ' input ' . "\n" . ' ',
                '1787a15641b162ed2fed4ce515abcb45'
            ],
            [
                '',
                'd41d8cd98f00b204e9800998ecf8427e'
            ]
        ];
    }

    /**
     * @test
     * @group vd
     * @group vd_prestations
     * @dataProvider generateUniqueIdFailureDataProvider
     */
    public function generateUniqueIdFailureWithMD5Length(string $input, string $notExpected): void
    {
        $this->assertNotSame($notExpected, StringUtility::generateUniqueId($input));
    }

    /**
     * @test
     * @group vd
     * @group vd_prestations
     */
    public function generateUniqueIdProducesConsistentOutput(): void
    {
        $uniqueId = StringUtility::generateUniqueId('consistent input');

        $this->assertSame($uniqueId, $uniqueId);
    }

    /**
     * @test
     * @group vd
     * @group vd_prestations
     * @dataProvider generateUniqueIdDataProvider
     */
    public function generateUniqueIdTest(string $input, string $expected): void
    {
        $this->assertSame($expected, StringUtility::generateUniqueId($input));
    }

    public function removeWhiteSpacesDataProvider(): array
    {
        return [
            [
                '  This is a multiple string' . "\n" . 'with white' . "\r \r\n" . ' spaces  ',
                'This is a multiple string with white spaces'
            ],
            [
                '  leading and trailing spaces  ',
                'leading and trailing spaces'
            ],
            [
                'singleWord',
                'singleWord'
            ],
            [
                '  ',
                ''],
            [
                'noSpaces',
                'noSpaces'
            ]
        ];
    }

    /**
     * @test
     * @group vd
     * @group vd_prestations
     * @dataProvider removeWhiteSpacesDataProvider
     */
    public function removeWhiteSpacesTest(string $input, string $expected): void
    {
        $this->assertSame($expected, StringUtility::removeWhiteSpaces($input));
    }
}
