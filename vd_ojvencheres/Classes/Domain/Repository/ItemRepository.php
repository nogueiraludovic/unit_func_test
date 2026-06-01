<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use Vd\VdOjvencheres\Domain\Model\Dto\AbstractDemand;
use Vd\VdOjvencheres\Domain\Model\Dto\ItemDemand;
use Vd\VdOjvencheres\Persistence\AbstractRepository;

use function time;

class ItemRepository extends AbstractRepository
{
    public function findDemanded(?AbstractDemand $demand = null): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $constraints = [];

        /** @var ItemDemand $demand */
        if ($demand !== null) {
            $category = $demand->getCategory();

            if ($category !== -1) {
                $constraints[] = $query->equals('itemCategories', $category);
            }

            $subCategory = $demand->getSubCategory();

            if ($subCategory !== -1) {
                $constraints[] = $query->equals('itemSubCategories', $subCategory);
            }

            $searchWord = $demand->getSearchWord();

            if ($searchWord !== '') {
                $constraints[] = $query->logicalOr([
                    $query->like('city', '%' . $searchWord . '%'),
                    $query->like('description', '%' . $searchWord . '%'),
                    $query->like('name', '%' . $searchWord . '%')
                ]);
            }
        }

        $constraints[] = $query->logicalAnd([
            $query->greaterThanOrEqual('sale.saleDate.saleDate', time() - 86400),
            $query->lessThanOrEqual('sale.pubDate', time())
        ]);

        return $query
            ->matching($query->logicalAnd($constraints))
            ->setOrderings(['sale.saleDate.saleDate' => QueryInterface::ORDER_ASCENDING])
            ->execute();
    }
}
