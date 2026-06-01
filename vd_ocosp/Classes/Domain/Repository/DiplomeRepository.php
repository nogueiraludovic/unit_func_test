<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Vd\VdOcosp\Domain\Model\Diplome;

class DiplomeRepository extends Repository
{
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING
    ];

    public function findHighestSorting(): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->setOrderings([
                'sorting' => QueryInterface::ORDER_DESCENDING
            ])
            ->setLimit(1)
            ->execute();
    }

    public function findNext(Diplome $diplome): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->matching($query->greaterThan('sorting', $diplome->getSorting()))
            ->setOrderings([
                'sorting' => QueryInterface::ORDER_ASCENDING
            ])
            ->setLimit(1)
            ->execute();
    }

    public function findPrevious(Diplome $diplome): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->matching($query->lessThan('sorting', $diplome->getSorting()))
            ->setOrderings([
                'sorting' => QueryInterface::ORDER_DESCENDING
            ])
            ->setLimit(1)
            ->execute();
    }

    public function initializeObject(): void
    {
        $this->setDefaultQuerySettings(
            GeneralUtility::makeInstance(Typo3QuerySettings::class)->setRespectStoragePage(false)
        );
    }
}
