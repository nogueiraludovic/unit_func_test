<?php

declare(strict_types=1);

namespace Vd\VdContactService\Tests\Unit\ViewHelpers\Format;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdContactService\ViewHelpers\Format\TelephoneViewHelper;

final class TelephoneViewHelperTest extends UnitTestCase
{
    /**
     * @dataProvider formatProvider
     * @test
     */
    public function checkRenderFormatsTelephoneCorrectly(string $input, string $expected): void
    {
        $subject = new TelephoneViewHelper();
        $subject->setRenderChildrenClosure(static fn () => $input);

        /** @noinspection PhpParamsInspection */
        self::assertSame($expected, $subject->render());
    }

    public static function formatProvider(): array
    {
        return [
            'Dot separated' => ['079.123.45.67', '+41791234567'],
            'Hyphenated +41' => ['+41-79-123-45-67', '+41791234567'],
            'International +41' => ['+41 79 123 45 67', '+41791234567'],
            'International 0041' => ['0041 79 123 45 67', '+41791234567'],
            'Slash separated' => ['079/123/45/67', '+41791234567'],
            'Space separated' => ['079 123 45 67', '+41791234567']
        ];
    }
}
