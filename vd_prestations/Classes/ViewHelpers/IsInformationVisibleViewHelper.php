<?php

declare(strict_types=1);

namespace Vd\VdPrestations\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Vd\VdPrestations\Domain\Model\Prestation;

class IsInformationVisibleViewHelper extends AbstractViewHelper
{
    public function render(): bool
    {
        /** @var Prestation $prestation */
        $prestation = $this->templateVariableContainer->get('prestation');

        /** @noinspection NullPointerExceptionInspection */
        return $prestation->getEmolument() !== ''
            || $prestation->getHelpLink() !== ''
            || $prestation->getLegalReferences()->count() > 0
            || $prestation->getRelatedPages()->count() > 0
            || $prestation->getRelatedPrestations()->count() > 0;
    }
}
