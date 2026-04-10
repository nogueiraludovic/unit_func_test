<?php

declare(strict_types=1);

namespace Vd\VdCore\ViewHelpers;

/** @noinspection PhpDeprecationInspection */
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

use function is_array;

final class StringContainsViewHelper extends AbstractConditionViewHelper
{
    #[CodeCoverageIgnore]
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument('caseSensitive', 'bool', '', false, false);
        $this->registerArgument('haystack', 'string', '', true);
        $this->registerArgument('needle', 'array|string', '', true);
    }

    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        $function = $arguments['caseSensitive'] === false ? 'stripos' : 'strpos';

        if (is_array($arguments['needle']) === true) {
            foreach ($arguments['needle'] as $needle) {
                if ($function($arguments['haystack'], $needle) !== false) {
                    return true;
                }
            }
        } elseif ($function($arguments['haystack'], $arguments['needle']) !== false) {
            return true;
        }

        return false;
    }
}
