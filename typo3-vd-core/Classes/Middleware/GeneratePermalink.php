<?php

declare(strict_types=1);

namespace Vd\VdCore\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\NullResponse;
use TYPO3\CMS\Core\Http\Stream;

use function str_replace;

final class GeneratePermalink implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if (
            ($response instanceof NullResponse) === true
            || $request->getAttribute('frontend.controller')->isINTincScript() === true
        ) {
            $replace = '';
        } else {
            $replace = $this->getPermalink($request->getUri(), $request->getAttribute('routing')->getPageId());
        }

        $body = new Stream('php://temp', 'rw');
        $body->write(str_replace('###PERMALINKCOMMENT###', $replace, (string)$response->getBody()));

        return $response->withBody($body);
    }

    private function getPermalink(UriInterface $uri, int $id): string
    {
        return '<!-- Permalien : ' . $uri->getScheme() . '://' . $uri->getHost() . '/page/' . $id . ' -->';
    }
}
