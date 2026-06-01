<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\TCA\Evaluation;

class YearEvaluation extends AbstractEvaluation
{
    public function evaluateFieldValue(string $value, string $_, bool &$set): string
    {
        return $this->evaluate4DigitsFieldValue($value, $set, 'year');
    }
}
