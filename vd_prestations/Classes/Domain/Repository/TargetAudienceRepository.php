<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Vd\VdPrestations\Domain\Model\TargetAudience;

class TargetAudienceRepository extends AbstractRepository
{
    public function findByName(string $name): ?TargetAudience
    {
        $query = $this->createQuery();

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $query
            ->matching($query->equals('name', $name))
            ->setOrderings([
                'name' => QueryInterface::ORDER_ASCENDING
            ])
            ->execute()
            ->getFirst();
    }
}
