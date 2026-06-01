<?php

declare(strict_types=1);

namespace Vd\VdDirectory\ViewHelpers\Format;

use Closure;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

use function implode;

class PhonesViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    protected $escapeChildren = false;
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('phone', 'array', '');
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $records = $renderChildrenClosure();

        foreach ($records as $key => $record) {
            $records[$key] = '<a href="tel:' . preg_replace('/[^0-9+]/', '', $record['phone']) . '" id="phone-link-' . $key . '" rel="nofollow">'
                . $record['phone']
                . '</a>';
        }

        $recordsHtml = implode('</li><li>', $records);

        return '<ul class="list-unstyled">' . ($recordsHtml === '' ? '' : '<li>' . $recordsHtml . '</li>') . '</ul>';
    }
}
