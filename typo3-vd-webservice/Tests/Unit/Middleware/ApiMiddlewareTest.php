<?php

declare(strict_types=1);

namespace Vd\VdWebservice\Tests\Unit\Middleware;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Http\ServerRequest;
use Vd\VdWebservice\Middleware\ApiMiddleware;
use Vd\VdWebservice\Query\BackendUsersQuery;
use Vd\VdWebservice\Query\SmallAdsQuery;

final class ApiMiddlewareTest extends TestCase
{
    private MockObject $backendUsersQuery;
    private MockObject $smallAdsQuery;
    private RequestHandlerInterface $passHandler;

    #[Test]
    public function processPassesRequestToHandlerWhenPathDoesNotMatch(): void
    {
        $request = new ServerRequest('/other/path');

        $response = $this->createMiddleware(['api_key' => 'key', 'api_authorized_ips' => ''])
            ->process($request, $this->passHandler);

        $this->assertSame(200, $response->getStatusCode());
    }

    #[Test]
    public function processThrowsWhenApiKeyConfigIsEmpty(): void
    {
        $this->expectException(RuntimeException::class);

        $request = (new ServerRequest('/webservice/api'))
            ->withQueryParams(['tx_webservice' => ['authkey' => 'key']]);

        $this->createMiddleware(['api_key' => '', 'api_authorized_ips' => ''])
            ->process($request, $this->passHandler);
    }

    #[Test]
    public function processThrowsWhenAuthKeyIsMissing(): void
    {
        $this->expectException(RuntimeException::class);

        $request = (new ServerRequest('/webservice/api'))
            ->withQueryParams(['tx_webservice' => []]);

        $this->createMiddleware(['api_key' => 'secret', 'api_authorized_ips' => ''])
            ->process($request, $this->passHandler);
    }

    #[Test]
    public function processThrowsWhenAuthKeyIsWrong(): void
    {
        $this->expectException(RuntimeException::class);

        $request = (new ServerRequest('/webservice/api'))
            ->withQueryParams(['tx_webservice' => ['authkey' => 'wrong']]);

        $this->createMiddleware(['api_key' => 'secret', 'api_authorized_ips' => ''])
            ->process($request, $this->passHandler);
    }

    #[Test]
    public function processAllowsRequestWhenIpListIsEmpty(): void
    {
        $this->backendUsersQuery->method('fetchAllContributors')->willReturn([]);

        $request = (new ServerRequest('/webservice/api'))
            ->withQueryParams(['tx_webservice' => ['authkey' => 'key', 'service' => 'contributors']]);

        $response = $this->createMiddleware(['api_key' => 'key', 'api_authorized_ips' => ''])
            ->process($request, $this->passHandler);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    #[Test]
    public function processAllowsRequestWhenIpListIsWildcard(): void
    {
        $this->backendUsersQuery->method('fetchAllContributors')->willReturn([]);

        $request = (new ServerRequest('/webservice/api'))
            ->withQueryParams(['tx_webservice' => ['authkey' => 'key', 'service' => 'contributors']]);

        $response = $this->createMiddleware(['api_key' => 'key', 'api_authorized_ips' => '*'])
            ->process($request, $this->passHandler);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    #[Test]
    public function processThrowsWhenClientIpIsNotInAllowedList(): void
    {
        $this->expectException(RuntimeException::class);

        $request = (new ServerRequest('/webservice/api'))
            ->withQueryParams(['tx_webservice' => ['authkey' => 'key']])
            ->withServerParams(['HTTP_X_FORWARDED_FOR' => '9.9.9.9']);

        $this->createMiddleware(['api_key' => 'key', 'api_authorized_ips' => '1.2.3.4'])
            ->process($request, $this->passHandler);
    }

    #[Test]
    public function processAllowsRequestWhenClientIpIsInAllowedList(): void
    {
        $this->backendUsersQuery->method('fetchAllContributors')->willReturn([]);

        $request = (new ServerRequest('/webservice/api'))
            ->withQueryParams(['tx_webservice' => ['authkey' => 'key', 'service' => 'contributors']])
            ->withServerParams(['HTTP_X_FORWARDED_FOR' => '1.2.3.4']);

        $response = $this->createMiddleware(['api_key' => 'key', 'api_authorized_ips' => '1.2.3.4'])
            ->process($request, $this->passHandler);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    #[Test]
    public function processReturnsContributorsJsonForContributorsService(): void
    {
        $this->backendUsersQuery->method('fetchAllContributors')->willReturn([['uid' => 1, 'username' => 'alice']]);

        $request = (new ServerRequest('/webservice/api'))
            ->withQueryParams(['tx_webservice' => ['authkey' => 'key', 'service' => 'contributors']]);

        $response = $this->createMiddleware(['api_key' => 'key', 'api_authorized_ips' => ''])
            ->process($request, $this->passHandler);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame('[{"uid":1,"username":"alice"}]', (string)$response->getBody());
    }

    #[Test]
    public function processReturnsSmallAdsJsonForSmallAdsService(): void
    {
        $this->smallAdsQuery->method('fetchAll')->willReturn([['uid' => 42, 'title' => 'Bike']]);

        $request = (new ServerRequest('/webservice/api'))
            ->withQueryParams(['tx_webservice' => ['authkey' => 'key', 'service' => 'smallads']]);

        $response = $this->createMiddleware(['api_key' => 'key', 'api_authorized_ips' => ''])
            ->process($request, $this->passHandler);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame('[{"uid":42,"title":"Bike"}]', (string)$response->getBody());
    }

    #[Test]
    public function processReturnsSmallAdsJsonForKesmallAdsService(): void
    {
        $this->smallAdsQuery->method('fetchAll')->willReturn([['uid' => 7]]);

        $request = (new ServerRequest('/webservice/api'))
            ->withQueryParams(['tx_webservice' => ['authkey' => 'key', 'service' => 'kesmallads']]);

        $response = $this->createMiddleware(['api_key' => 'key', 'api_authorized_ips' => ''])
            ->process($request, $this->passHandler);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame('[{"uid":7}]', (string)$response->getBody());
    }

    #[Test]
    public function processReturnsErrorJsonForUnknownService(): void
    {
        $request = (new ServerRequest('/webservice/api'))
            ->withQueryParams(['tx_webservice' => ['authkey' => 'key', 'service' => 'unknown']]);

        $response = $this->createMiddleware(['api_key' => 'key', 'api_authorized_ips' => ''])
            ->process($request, $this->passHandler);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->backendUsersQuery = $this->createMock(BackendUsersQuery::class);
        $this->smallAdsQuery = $this->createMock(SmallAdsQuery::class);

        $this->passHandler = new class () implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new JsonResponse(['pass' => true]);
            }
        };
    }

    private function createMiddleware(array $config): ApiMiddleware
    {
        return new class ($this->backendUsersQuery, $config, $this->smallAdsQuery) extends ApiMiddleware {
            protected function accessDeniedAction(string $message = 'Service is not accessible'): void
            {
                throw new RuntimeException($message, 1659947088);
            }
        };
    }
}
