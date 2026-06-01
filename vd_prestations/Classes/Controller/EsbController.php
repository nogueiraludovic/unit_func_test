<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdPrestations\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Http\RequestHandler;
use Vd\VdPrestations\Middleware\ApiMiddleware;

class EsbController
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $api = GeneralUtility::makeInstance(ApiMiddleware::class);
        $api->setEid(true);

        return $api->process($request, GeneralUtility::makeInstance(RequestHandler::class));
    }
}
