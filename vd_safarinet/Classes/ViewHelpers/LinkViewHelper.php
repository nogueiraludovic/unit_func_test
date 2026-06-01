<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\ViewHelpers;

use Closure;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Vd\VdSafarinet\Service\LinkService;

class LinkViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this
            ->registerArgument('meetingGcId', 'mixed', '')
            ->registerArgument('text', 'mixed', '');
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        return GeneralUtility::makeInstance(LinkService::class, $renderingContext->getControllerContext())->parse(
            (string)$arguments['text'],
            (int)$arguments['meetingGcId']
        );
    }
}
