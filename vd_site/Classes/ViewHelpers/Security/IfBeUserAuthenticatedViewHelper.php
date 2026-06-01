<?php

declare(strict_types=1);

namespace Vd\VdSite\ViewHelpers\Security;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class IfBeUserAuthenticatedViewHelper extends AbstractConditionViewHelper
{
    protected static function evaluateCondition($arguments = null): bool
    {
        return GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('backend.user', 'isLoggedIn');
    }
}
