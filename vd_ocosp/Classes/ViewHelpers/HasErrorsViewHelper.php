<?php

namespace Vd\VdOcosp\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class HasErrorsViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument('for', 'string', '', false, '');
    }

    public function render(): string
    {
        $for = $this->arguments['for'];
        $errors = $this->renderingContext
            ->getControllerContext()
            ->getRequest()
            ->getOriginalRequestMappingResults()
            ->getFlattenedErrors();

        // If the "for" argument is not defined, we are just interested in the general error count
        if (empty($for)) {
            if (count($errors) > 0) {
                return $this->renderThenChild();
            }

            return $this->renderElseChild();
        }

        if (isset($errors[$for]) > 0) {
            return $this->renderThenChild();
        }

        return $this->renderElseChild();
    }
}
