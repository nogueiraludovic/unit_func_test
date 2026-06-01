<?php

namespace Vd\VdOcosp\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;
use Vd\VdOcosp\Domain\Model\Domaine;

class HasFilterViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments(): void
    {
        $this
            ->registerArgument('school', 'string', '', true)
            ->registerArgument('training', 'string', '', true)
            ->registerArgument('domain', Domaine::class, '', true);
    }

    public function render()
    {
        return $this->arguments['domain'] !== null
            || $this->arguments['school'] !== ''
            || $this->arguments['training'] !== '';
    }
}
