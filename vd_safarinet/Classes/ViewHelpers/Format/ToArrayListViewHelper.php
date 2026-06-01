<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\ViewHelpers\Format;

use Closure;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

use function array_keys;
use function count;
use function range;

class ToArrayListViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'array', '');
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): array {
        $value = (array)$renderChildrenClosure();

        if ($value === []) {
            return $value;
        }

        if (array_keys($value) === range(0, count($value) - 1)) {
            return $value;
        }

        $arrayList[] = $value;

        return $arrayList;
    }
}
