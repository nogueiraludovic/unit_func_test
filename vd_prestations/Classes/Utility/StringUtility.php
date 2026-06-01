<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Utility;

class StringUtility
{
    public static function stringToBool(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function removeWhiteSpaces(string $value): string
    {
        $value = trim($value);
        $value = preg_replace('!\s+!', ' ', $value);
        $value = str_replace("\n", '', $value);

        return str_replace("\r", '', $value);
    }

    public static function generateUniqueId(string $value): string
    {
        return substr(md5(self::removeWhiteSpaces($value)), 0, 12);
    }
}
