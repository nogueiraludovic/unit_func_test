<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\ViewHelpers;

use Closure;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function getenv;
use function sprintf;

class DocumentViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this
            ->registerArgument('document', 'mixed', '')
            ->registerArgument('urlOnly', 'bool', '', false, false);
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $title = $arguments['document']['description'] ?? $arguments['document']['titre'];
        $url = getenv('TYPO3_EXT_VD_SAFARINET_DOCUMENT_SERVICE_URL') . $arguments['document']['id'];

        if ($arguments['urlOnly'] === false) {
            /** @noinspection HtmlUnknownTarget */
            $value = sprintf('<a href="%s" title="%s">%s</a>', $url, $title, $title);
        } else {
            $value = $url;
        }

        return $value;
    }
}
