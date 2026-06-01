<?php

declare(strict_types=1);

namespace Vd\VdPowermail\ViewHelpers\Validation;

use In2code\Powermail\Domain\Model\Field;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class HasErrorViewHelper extends AbstractConditionViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument('field', Field::class, '', true);
    }

    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $flattenedErrors = $renderingContext
            ->getControllerContext()
            ->getRequest()
            ->getOriginalRequestMappingResults()
            ->getFlattenedErrors();

        foreach ($flattenedErrors as $errors) {
            foreach ($errors as $error) {
                if ($arguments['field']->getMarker() === $error->getCode()) {
                    return true;
                }
            }
        }

        return false;
    }
}
