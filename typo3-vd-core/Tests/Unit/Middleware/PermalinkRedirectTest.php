<?php

declare(strict_types=1);

namespace Vd\VdCore\Tests\Unit\Middleware;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Response;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException;
use Vd\VdCore\Middleware\PermalinkRedirect;

final class PermalinkRedirectTest extends TestCase
{
    #[Test]
    public function returnsHandlerResponseWhenPartsAreNotTwo(): void
    {
        $routing = new class () {
            public function getTail(): string
            {
                return 'invalid';
            }
        };

        $handler = new class () implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new RedirectResponse('/fallback');
            }
        };

        $request = new ServerRequest('/');
        $request = $request->withAttribute('routing', $routing);

        $response = (new PermalinkRedirect())->process($request, $handler);

        $this->assertSame('/fallback', $response->getHeaderLine('Location'));
    }

    #[Test]
    public function returnsHandlerResponseWhenPrefixIsNotPage(): void
    {
        $routing = new class () {
            public function getTail(): string
            {
                return 'foo/123';
            }
        };

        $handler = new class () implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new RedirectResponse('/fallback2');
            }
        };

        $request = new ServerRequest('/');
        $request = $request->withAttribute('routing', $routing);

        $response = (new PermalinkRedirect())->process($request, $handler);

        $this->assertSame('/fallback2', $response->getHeaderLine('Location'));
    }

    #[Test]
    public function returnsHandlerResponseWhenPidIsNotInteger(): void
    {
        $routing = new class () {
            public function getTail(): string
            {
                return 'page/abc';
            }
        };

        $handler = new class () implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new RedirectResponse('/fallback3');
            }
        };

        $request = new ServerRequest('/');
        $request = $request->withAttribute('routing', $routing);

        $response = (new PermalinkRedirect())->process($request, $handler);

        $this->assertSame('/fallback3', $response->getHeaderLine('Location'));
    }

    #[Test]
    public function returnsHandlerResponseWhenRouterThrowsException(): void
    {
        $router = new class () {
            public function generateUri(int $pid, array $args): string
            {
                throw new InvalidRouteArgumentsException();
            }
        };

        $site = new class ($router) {
            public function __construct(private $router)
            {
            }
            public function getRouter()
            {
                return $this->router;
            }
        };

        $routing = new class () {
            public function getTail(): string
            {
                return 'page/12';
            }
        };

        $handler = new class () implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new RedirectResponse('/fallback4');
            }
        };

        $request = new ServerRequest('/');
        $request = $request->withAttribute('routing', $routing);
        $request = $request->withAttribute('site', $site);
        $request = $request->withAttribute('language', 0);

        $response = (new PermalinkRedirect())->process($request, $handler);

        $this->assertSame('/fallback4', $response->getHeaderLine('Location'));
    }

    #[Test]
    public function redirectsSuccessfully(): void
    {
        $router = new class () {
            public function generateUri(int $pid, array $args): string
            {
                return '/generated/' . $pid;
            }
        };

        $site = new class ($router) {
            public function __construct(private $router)
            {
            }
            public function getRouter()
            {
                return $this->router;
            }
        };

        $routing = new class () {
            public function getTail(): string
            {
                return 'page/42';
            }
        };

        $handler = new class () implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new RedirectResponse('/should-not-be-used');
            }
        };

        $request = new ServerRequest('/');
        $request = $request->withAttribute('routing', $routing);
        $request = $request->withAttribute('site', $site);
        $request = $request->withAttribute('language', 1);

        $response = (new PermalinkRedirect())->process($request, $handler);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/generated/42', $response->getHeaderLine('Location'));
        $this->assertSame(Response::HTTP_MOVED_PERMANENTLY, $response->getStatusCode());
    }
}
