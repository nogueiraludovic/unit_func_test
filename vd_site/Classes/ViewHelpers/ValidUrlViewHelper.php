<?php

declare(strict_types=1);

namespace Vd\VdSite\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class ValidUrlViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('url', 'string', '', true);
    }

    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        return GeneralUtility::isValidUrl($arguments['url']);
    }
}
