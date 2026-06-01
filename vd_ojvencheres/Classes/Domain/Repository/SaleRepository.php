<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use Vd\VdOjvencheres\Domain\Model\Dto\AbstractDemand;
use Vd\VdOjvencheres\Domain\Model\Dto\SaleDemand;
use Vd\VdOjvencheres\Persistence\AbstractRepository;

use function time;

class SaleRepository extends AbstractRepository
{
    public function findDemanded(?AbstractDemand $demand = null): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $constraints = [];

        /** @var SaleDemand $demand */
        if ($demand !== null) {
            $itemCategory = $demand->getItemCategory();

            if ($itemCategory !== -1) {
                $constraints[] = $query->equals('saleItems.itemCategories', $itemCategory);
            }

            $mainOffice = $demand->getMainOffice();

            if ($mainOffice !== -1) {
                $constraints[] = $query->equals('mainOffice', $mainOffice);
            }

            $saleCategory = $demand->getSaleCategory();

            if ($saleCategory !== -1) {
                $constraints[] = $query->equals('saleCategories', $saleCategory);
            }
        }

        $constraints[] = $query->logicalAnd([
            $query->greaterThanOrEqual('saleDate.saleDate', time() - 86400),
            $query->lessThanOrEqual('pubDate', time())
        ]);

        return $query
            ->matching($query->logicalAnd($constraints))
            ->setOrderings(['saleDate.saleDate' => QueryInterface::ORDER_ASCENDING])
            ->execute();
    }
}
