<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Vd\VdSmallAds\DataTransferObject\Demand;

class SmallAdsRepository extends Repository
{
    protected $defaultOrderings = [
        'crdateexternal' => QueryInterface::ORDER_DESCENDING
    ];

    public function findAdTypes(): array
    {
        return $this->findTypes('cat');
    }

    public function findAll(
        bool $ignoreEnableFields = false,
        bool $includeDeleted = false,
        bool $respectStoragePage = true
    ): QueryResultInterface {
        $query = $this->createQuery();
        $query
            ->getQuerySettings()
            ->setIgnoreEnableFields($ignoreEnableFields)
            ->setIncludeDeleted($includeDeleted)
            ->setRespectStoragePage($respectStoragePage);

        return $query->execute();
    }

    public function findDemanded(?Demand $demand = null): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        if (($demand instanceof Demand) === false) {
            return $query->execute();
        }

        $constraints = [];

        $adType = $demand->getAdType();

        if ($adType !== '') {
            $constraints[] = $query->equals('cat', $adType);
        }

        $objectType = $demand->getObjectType();

        if ($objectType !== '') {
            $constraints[] = $query->equals('cat2', $objectType);
        }

        $search = $demand->getQuery();

        if ($search !== '') {
            $constraints[] = $query->logicalOr(
                $query->like('content', '%' . $search . '%'),
                $query->like('title', '%' . $search . '%')
            );
        }

        if ($constraints !== []) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        return $query->execute();
    }

    public function findObjectTypes(): array
    {
        return $this->findTypes('cat2');
    }

    public function removeAll(): void
    {
        $objects = $this->findAll(true, true, false);

        foreach ($objects as $object) {
            $this->remove($object);
        }
    }

    protected function findTypes(string $field): array
    {
        $query = $this->createQuery();

        return $query
            ->statement('SELECT DISTINCT `' . $field . '` AS `name` FROM `tx_vdsmallads_domain_model_smallads` WHERE `' . $field . '`<>\'\' AND `deleted`=0 AND `hidden`=0 ORDER BY `' . $field . '` ASC')
            ->execute(true);
    }
}
