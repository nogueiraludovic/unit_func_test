<?php

declare(strict_types=1);

namespace Vd\VdLada\ViewHelpers\Format;

use Closure;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

use function preg_replace;

class PhoneViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    protected $escapeChildren = false;
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this
            ->registerArgument('phone', 'string', '')
            ->registerArgument('identifier', 'string', '', false, '0');
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        return '<a class="phone-link" href="tel:' . preg_replace('/[^0-9+]/', '', $arguments['phone']) . '" id="phone-link-' . $arguments['identifier'] . '" rel="nofollow">'
            . $arguments['phone']
            . '</a>';
    }
}
