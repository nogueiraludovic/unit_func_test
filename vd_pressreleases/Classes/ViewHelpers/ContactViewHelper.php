<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\ViewHelpers;

use Closure;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Vd\VdPressreleases\Domain\Model\Contact;
use Vd\VdPressreleases\Domain\Model\PressRelease;

class ContactViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this
            ->registerArgument('contact', Contact::class, '', true)
            ->registerArgument('pressRelease', PressRelease::class, '', true);
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        return $arguments['contact']->render($arguments['pressRelease']);
    }
}
