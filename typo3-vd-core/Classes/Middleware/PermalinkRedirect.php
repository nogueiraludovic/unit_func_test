<?php

declare(strict_types=1);

namespace Vd\VdCore\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Response;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

use function count;

final class PermalinkRedirect implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $parts = GeneralUtility::trimExplode('/', $request->getAttribute('routing')->getTail(), true, 2);

        if (count($parts) !== 2) {
            return $handler->handle($request);
        }

        [$prefix, $pid] = $parts;

        if ($prefix !== 'page' || MathUtility::canBeInterpretedAsInteger($pid) === false) {
            return $handler->handle($request);
        }

        try {
            $uri = (string)$request
                ->getAttribute('site')
                ->getRouter()
                ->generateUri(
                    (int)$pid,
                    [
                        '_language' => $request->getAttribute('language'),
                    ]
                );
        } catch (InvalidRouteArgumentsException) {
            return $handler->handle($request);
        }

        return new RedirectResponse($uri, Response::HTTP_MOVED_PERMANENTLY);
    }
}
