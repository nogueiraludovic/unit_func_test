<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use Vd\VdOjvencheres\Domain\Model\Dto\ItemDemand;
use Vd\VdOjvencheres\Domain\Model\Dto\SaleDemand;
use Vd\VdOjvencheres\Domain\Repository\ItemRepository;
use Vd\VdOjvencheres\Domain\Repository\SaleRepository;
use Vd\VdOjvencheres\Service\FilterService;
use Vd\VdOjvencheres\Session\SessionStorage;

use function serialize;

class AjaxFilter implements MiddlewareInterface
{
    protected const TYPE_ITEM = 'item';
    protected const TYPE_SALE = 'sale';

    protected FilterService $filterService;
    protected ItemRepository $itemRepository;
    protected SaleRepository $saleRepository;
    protected SessionStorage $sessionStorage;

    public function __construct(
        FilterService $filterService,
        ItemRepository $itemRepository,
        SaleRepository $saleRepository,
        SessionStorage $sessionStorage
    ) {
        $this->filterService = $filterService;
        $this->itemRepository = $itemRepository;
        $this->saleRepository = $saleRepository;
        $this->sessionStorage = $sessionStorage;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        if (
            isset($queryParams['tx_vdojvencheres']) === false
            || (bool)$queryParams['tx_vdojvencheres']['ajax-filter'] === false
        ) {
            return $handler->handle($request);
        }

        $queryParams = $queryParams['tx_vdojvencheres'];

        switch ($this->getType($queryParams)) {
            case self::TYPE_ITEM:
                $filters = $this->handleItemType($queryParams);
                break;
            case self::TYPE_SALE:
                $filters = $this->handleSaleType($queryParams);
                break;
            default:
                $filters = [];
        }

        return new JsonResponse($filters);
    }

    protected function getType(array $queryParams): string
    {
        return isset($queryParams['categories'], $queryParams['subCategories']) === true
            ? self::TYPE_ITEM
            : self::TYPE_SALE;
    }

    protected function handleItemType(array $queryParams): array
    {
        $filters = $this->filterService->buildItemFilters(
            $this->itemRepository->findDemanded(new ItemDemand((int)($queryParams['categories'] ?? -1), '', -1))
        );

        $this->sessionStorage->push(['subCategories' => serialize($filters['subCategories'])]);

        return $filters;
    }

    protected function handleSaleType(array $queryParams): array
    {
        return $this->filterService->buildSaleFilters(
            $this->saleRepository->findDemanded(
                new SaleDemand(
                    (int)($queryParams['mainItemCategories'] ?? -1),
                    (int)($queryParams['mainOffices'] ?? -1),
                    (int)($queryParams['salesCategories'] ?? -1)
                )
            )
        );
    }
}
