<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Persistence;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Vd\VdOjvencheres\Domain\Model\Dto\AbstractDemand;

abstract class AbstractRepository extends Repository
{
    public function findByUid($uid, bool $respectEnableFields = true): ?object
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setRespectSysLanguage(false);

        if ($respectEnableFields === false) {
            $query->getQuerySettings()->setIgnoreEnableFields(true);
            $query->getQuerySettings()->setLanguageOverlayMode(false);
        }

        return $query
            ->matching($query->equals('uid', $uid))
            ->execute()
            ->getFirst();
    }

    abstract public function findDemanded(?AbstractDemand $demand): QueryResultInterface;
}
