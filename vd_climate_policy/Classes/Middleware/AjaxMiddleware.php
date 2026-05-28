<?php

declare(strict_types=1);

namespace Vd\VdClimatePolicy\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdClimatePolicy\Database\RecordRepository;

use function count;

class AjaxMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getUri()->getPath() !== '/climate-policy/ajax') {
            return $handler->handle($request);
        }

        $queryParams = $request->getQueryParams();
        $queryParams = $queryParams['tx_climatepolicy'] ?? [];

        $records = GeneralUtility::makeInstance(RecordRepository::class)
            ->fetchSelectItemsByParentUids($queryParams);

        if (count($records) === 0) {
            return new JsonResponse(null, 404);
        }

        return new JsonResponse($records);
    }
}
