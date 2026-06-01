<?php

declare(strict_types=1);

namespace Vd\VdOcosp\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function str_replace;

class TranslateDateViewHelper extends AbstractViewHelper
{
    public function render(): string
    {
        $searches[] = 'Monday';
        $searches[] = 'Tuesday';
        $searches[] = 'Wednesday';
        $searches[] = 'Thursday';
        $searches[] = 'Friday';
        $searches[] = 'Saturday';
        $searches[] = 'Sunday';

        $replaces[] = 'Lundi';
        $replaces[] = 'Mardi';
        $replaces[] = 'Mercredi';
        $replaces[] = 'Jeudi';
        $replaces[] = 'Vendredi';
        $replaces[] = 'Samedi';
        $replaces[] = 'Dimanche';

        return str_replace($searches, $replaces, $this->renderChildren());
    }
}
