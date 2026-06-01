<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Controller;

use Vd\VdCore\Utility\CacheUtility;
use Vd\VdOjvencheres\Domain\Model\Dto\SaleDemand;
use Vd\VdOjvencheres\Domain\Model\Sale;
use Vd\VdOjvencheres\Domain\Repository\SaleRepository;
use Vd\VdOjvencheres\Mvc\Controller\AbstractController;

class SaleController extends AbstractController
{
    protected SaleRepository $saleRepository;

    public function injectSaleRepository(SaleRepository $saleRepository): void
    {
        $this->saleRepository = $saleRepository;
    }

    public function listAction(SaleDemand $demand = null): void
    {
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $demand = $this->createDemand($demand);
        $sales = $this->saleRepository->findDemanded($demand);
        $filters = $this->filterService->buildSaleFilters($sales);

        $this->initializePagination($sales);
        $this->view->assignMultiple([
            'demand' => $demand,
            'mainItemCategories' => $filters['mainItemCategories'],
            'mainOffices' => $filters['mainOffices'],
            'numberOfRecords' => $sales->count(),
            'sales' => $sales,
            'salesCategories' => $filters['salesCategories']
        ]);

        CacheUtility::addCacheTagsForRecords('tx_vdojvencheres', $sales);
    }

    public function showAction(Sale $sale = null): void
    {
        if ($sale === null) {
            $arguments = $this->getRequest()->getQueryParams();

            if ((bool)$arguments['no_cache'] === true) {
                $uid = (int)$arguments['tx_vdojvencheres_sale']['sale_preview'];

                if ($uid > 0) {
                    /** @noinspection CallableParameterUseCaseInTypeContextInspection */
                    $sale = $this->saleRepository->findByUid($uid, false);
                }
            }
        }

        if ($sale === null) {
            $this->pageNotFoundAction();
        }

        $this->view->assign('sale', $sale);

        CacheUtility::addCacheTagsForPages('tx_vdojvencheres', $this->getStoragePids());
        CacheUtility::addCacheTagsForRecord('tx_vdojvencheres', $sale);
    }
}
