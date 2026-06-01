<?php

declare(strict_types=1);

namespace Vd\VdPrestations\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Vd\VdPrestations\Domain\Model\AccessModality;

class IsHighLightVisibleViewHelper extends AbstractViewHelper
{
    public function render(): bool
    {
        /** @var AccessModality $accessModality */
        $accessModality = $this->templateVariableContainer->get('accessModality');

        return $accessModality->getAdditionalInformations()
            || $accessModality->getRequiredDocuments()
            || $accessModality->getCost()
            || $accessModality->getAverageDelay();
    }
}
