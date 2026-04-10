<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdCore\Mvc;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Frontend\Page\PageAccessFailureReasons;

trait PageNotFoundTrait
{
    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }

    protected function pageNotFoundAction(string $message = 'The requested page does not exist!'): void
    {
        $request = $this->getRequest();

        throw new ImmediateResponseException(
            GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction(
                $request,
                $message,
                $request->getAttribute('frontend.controller')->getPageAccessFailureReasons(
                    PageAccessFailureReasons::PAGE_NOT_FOUND
                )
            ),
            1659947088
        );
    }
}
