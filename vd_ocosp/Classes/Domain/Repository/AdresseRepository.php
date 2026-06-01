<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class AdresseRepository extends Repository
{
    protected $defaultOrderings = [
        'nom' => QueryInterface::ORDER_ASCENDING
    ];

    public function findBySearch(string $keyword): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->matching($query->like('nom', '%' . $keyword . '%'))
            ->execute();
    }
}
