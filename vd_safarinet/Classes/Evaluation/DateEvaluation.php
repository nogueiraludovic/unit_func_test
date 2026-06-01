<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Evaluation;

use DateTime;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_reverse;
use function implode;

class DateEvaluation
{
    public static function getDateValue(string $day, string $month, string $year): string
    {
        $value = '-' . $year;

        if ((int)$month <= 9) {
            $value = '-0' . ((int)$month) . $value;
        } else {
            $value = '-' . ((int)$month) . $value;
        }

        if ((int)$day <= 9) {
            $value = '0' . ((int)$day) . $value;
        } else {
            $value = ((int)$day) . $value;
        }

        return implode('-', array_reverse(GeneralUtility::trimExplode('-', $value), true));
    }

    public static function validateDate(string $date, string $format = 'Y-m-d'): bool
    {
        $date2 = DateTime::createFromFormat($format, $date);

        return $date2 && $date2->format($format) === $date;
    }
}
