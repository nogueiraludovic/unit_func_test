<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Tests\Evaluation;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdSafarinet\Evaluation\DateEvaluation;

final class DateEvaluationTest extends UnitTestCase
{
    public function testGetDateValueReturnsCorrectFormat(): void
    {
        // Test with single-digit day and month
        $result = DateEvaluation::getDateValue('5', '7', '2023');
        $this->assertEquals('2023-07-05', $result);

        // Test with double-digit day and month
        $result = DateEvaluation::getDateValue('15', '11', '2022');
        $this->assertEquals('2022-11-15', $result);

        // Edge case: Leap year
        $result = DateEvaluation::getDateValue('29', '2', '2020');
        $this->assertEquals('2020-02-29', $result);
    }

    public function testValidateDateReturnsTrueForValidDates(): void
    {
        // Valid date in standard format
        $result = DateEvaluation::validateDate('2023-07-05');
        $this->assertTrue($result);

        // Leap year
        $result = DateEvaluation::validateDate('2020-02-29');
        $this->assertTrue($result);
    }

    public function testValidateDateReturnsFalseForInvalidDates(): void
    {
        // Non-existent date
        $result = DateEvaluation::validateDate('2023-02-30');
        $this->assertFalse($result);

        // Invalid format
        $result = DateEvaluation::validateDate('05-07-2023');
        $this->assertFalse($result);

        // Invalid leap year date
        $result = DateEvaluation::validateDate('2019-02-29');
        $this->assertFalse($result);
    }

    public function testValidateDateSupportsCustomFormat(): void
    {
        // Valid date in custom format
        $result = DateEvaluation::validateDate('05-07-2023', 'd-m-Y');
        $this->assertTrue($result);

        // Invalid date in custom format
        $result = DateEvaluation::validateDate('30-02-2023', 'd-m-Y');
        $this->assertFalse($result);
    }
}
