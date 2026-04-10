<?php

declare(strict_types=1);

namespace Vd\VdWebservice\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use Vd\VdWebservice\Query\BackendUsersQuery;
use Vd\VdWebservice\Query\SmallAdsQuery;

use function in_array;

class ApiMiddleware implements MiddlewareInterface
{
    protected ServerRequestInterface $request;

    public function __construct(
        protected readonly BackendUsersQuery $backendUsersQuery,
        protected readonly array $extensionConfiguration,
        protected readonly SmallAdsQuery $smallAdsQuery
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->request = $request;

        if ($this->request->getUri()->getPath() !== '/webservice/api') {
            return $handler->handle($this->request);
        }

        $queryParams = (array)($request->getQueryParams()['tx_webservice'] ?? []);

        if ($this->isAuthenticationKeyAllowed($queryParams) === false || $this->isIpAllowed() === false) {
            $this->accessDeniedAction();
        }

        return match ((string)($queryParams['service'] ?? '')) {
            'contributors' => new JsonResponse($this->backendUsersQuery->fetchAllContributors()),
            'kesmallads', 'smallads' => new JsonResponse($this->smallAdsQuery->fetchAll()),
            default => new JsonResponse(['error' => 'Invalid service given'], 400)
        };
    }

    protected function accessDeniedAction(string $message = 'Service is not accessible'): void
    {
        throw new ImmediateResponseException(
            GeneralUtility::makeInstance(ErrorController::class)->accessDeniedAction($this->request, $message),
            1659947088
        );
    }

    protected function isAuthenticationKeyAllowed(array $queryParams): bool
    {
        $apiKey = (string)($this->extensionConfiguration['api_key'] ?? '');

        return $apiKey !== '' && (string)($queryParams['authkey'] ?? '') === $apiKey;
    }

    protected function isIpAllowed(): bool
    {
        $allowedIps = (string)($this->extensionConfiguration['api_authorized_ips'] ?? '');

        if ($allowedIps === '' || $allowedIps === '*') {
            return true;
        }

        $allowedIps = GeneralUtility::trimExplode(',', $allowedIps, true);

        if ($allowedIps === []) {
            return true;
        }

        $serversParams = $this->request->getServerParams();

        return in_array($serversParams['HTTP_X_FORWARDED_FOR'], $allowedIps, true) === true;
    }
}
