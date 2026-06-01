<?php

declare(strict_types=1);

namespace Vd\VdSite\ExpressionLanguage;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\ExpressionLanguage\AbstractProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SiteConditionProvider extends AbstractProvider
{
    public function __construct()
    {
        $request = $this->getRequest();

        $this->expressionLanguageVariables = [
            'httpHost' => $request === null
                ? GeneralUtility::getIndpEnv('HTTP_HOST')
                : $request->getAttribute('normalizedParams')->getHttpHost(),
            'isCli' => Environment::isCli() === true ? 'yes' : 'no'
        ];
    }

    protected function getRequest(): ?ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
