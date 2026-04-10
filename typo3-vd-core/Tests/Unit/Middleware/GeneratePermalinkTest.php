<?php

declare(strict_types=1);

namespace Vd\VdCore\Tests\Unit\Middleware;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\NullResponse;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\Stream;
use Vd\VdCore\Middleware\GeneratePermalink;

final class GeneratePermalinkTest extends TestCase
{
    #[Test]
    public function replacesWithEmptyStringForNullResponse(): void
    {
        $handler = new class () implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new NullResponse();
            }
        };

        $frontend = new class () {
            public function isINTincScript(): bool
            {
                return false;
            }
        };

        $routing = new class () {
            public function getPageId(): int
            {
                return 1;
            }
        };

        $request = new ServerRequest('/');
        $request = $request->withAttribute('frontend.controller', $frontend);
        $request = $request->withAttribute('routing', $routing);

        $middleware = new GeneratePermalink();
        $response = $middleware->process($request, $handler);

        $this->assertSame('', (string)$response->getBody());
    }

    #[Test]
    public function replacesWithEmptyStringWhenIntScript(): void
    {
        $handler = new class () implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $stream = new Stream('php://temp', 'rw');
                $stream->write('###PERMALINKCOMMENT###');
                return (new NullResponse())->withBody($stream);
            }
        };

        $frontend = new class () {
            public function isINTincScript(): bool
            {
                return true;
            }
        };

        $routing = new class () {
            public function getPageId(): int
            {
                return 2;
            }
        };

        $request = new ServerRequest('/');
        $request = $request->withAttribute('frontend.controller', $frontend);
        $request = $request->withAttribute('routing', $routing);

        $middleware = new GeneratePermalink();
        $response = $middleware->process($request, $handler);

        $this->assertSame('', (string)$response->getBody());
    }
}
