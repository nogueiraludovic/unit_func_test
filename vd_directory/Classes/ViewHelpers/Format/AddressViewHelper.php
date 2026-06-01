<?php

declare(strict_types=1);

namespace Vd\VdDirectory\ViewHelpers\Format;

use Closure;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

use function array_filter;
use function implode;
use function nl2br;
use function strip_tags;
use function trim;

class AddressViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    protected $escapeChildren = false;
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('record', 'array', '');
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $record = $renderChildrenClosure();

        $address[] = nl2br(strip_tags($record['address']));
        $address[] = trim($record['zip'] . ' ' . $record['city']);

        $addressHtml = implode('</li><li>', array_filter($address));

        return '<ul class="list-unstyled">' . ($addressHtml === '' ? '' : '<li>' . $addressHtml . '</li>') . '</ul>';
    }
}
