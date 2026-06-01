<?php

declare(strict_types=1);

namespace Vd\VdNews\ViewHelpers;

use TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper as DefaultTranslateViewHelper;

class TranslateViewHelper extends DefaultTranslateViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->overrideArgument('extensionName', 'string', '', false, 'VdNews');
    }
}
