<?php

declare(strict_types=1);

namespace Vd\VdCore\Middleware;

use ParagonIE\CSPBuilder\CSPBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Vd\VdCore\Resolver\ContentSecurityPolicyResolver;

final readonly class ContentSecurityPolicyHeader implements MiddlewareInterface
{
    public function __construct(private ContentSecurityPolicyResolver $contentSecurityPolicyResolver)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if (
            ($GLOBALS['TSFE'] instanceof TypoScriptFrontendController) === true
            && isset($GLOBALS['TSFE']->config['config']['enableCspHeader']) === true
            && (bool)$GLOBALS['TSFE']->config['config']['enableCspHeader'] === true
        ) {
            /** @noinspection PhpIncompatibleReturnTypeInspection */
            return (new CSPBuilder($this->contentSecurityPolicyResolver->resolve()))->injectCSPHeader($response);
        }

        return $response;
    }
}
