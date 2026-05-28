<?php

declare(strict_types=1);

namespace Vd\VdClimatePolicy\ViewHelpers;

use TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper as FluidTranslateViewHelper;

class TranslateViewHelper extends FluidTranslateViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->overrideArgument('extensionName', 'string', '', false, 'VdClimatePolicy');
    }
}
