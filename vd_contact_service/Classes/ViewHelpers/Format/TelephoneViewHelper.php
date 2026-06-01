<?php

declare(strict_types=1);

namespace Vd\VdContactService\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function preg_replace;
use function str_replace;

class TelephoneViewHelper extends AbstractViewHelper
{
    public function render(): string
    {
        return '+41' . preg_replace(
            '/^0/',
            '',
            str_replace(
                [
                    ' ',
                    '.',
                    '/',
                    '-',
                    '+41',
                    '0041'
                ],
                '',
                $this->renderChildren()
            )
        );
    }
}
