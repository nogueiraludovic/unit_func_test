<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class InteretRepository extends Repository
{
    protected $defaultOrderings = [
        'nom' => QueryInterface::ORDER_ASCENDING
    ];

    public function findByNameSearch(string $search): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->matching($query->like('nom', '%' . $search . '%'))
            ->execute();
    }
}
