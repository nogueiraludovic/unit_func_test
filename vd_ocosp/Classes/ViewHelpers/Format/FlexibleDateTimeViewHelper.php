<?php

declare(strict_types=1);

namespace Vd\VdOcosp\ViewHelpers\Format;

use Closure;
use DateTime;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class FlexibleDateTimeViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this
            ->registerArgument('date', DateTime::class, '')
            ->registerArgument('dateFormat', 'string', '', true)
            ->registerArgument('dateTimeFormat', 'string', '', false, '');
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $date = $arguments['date'];
        $dateFormat = $arguments['dateFormat'];
        $dateTimeFormat = $arguments['dateTimeFormat'];

        if ($date === null) {
            $date = $renderChildrenClosure();
        }

        if (($date instanceof DateTime) === false) {
            $date = new DateTime('@' . (int)$date);
        }

        if ($date->format('His') === '000000') {
            $result = $date->format($dateFormat);
        } else {
            $result = $date->format($dateTimeFormat);
        }

        return $result;
    }
}
