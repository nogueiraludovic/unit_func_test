<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class InscriptionRepository extends Repository
{
    protected $defaultOrderings = [
        'formation' => QueryInterface::ORDER_ASCENDING
    ];

    public function findBySearch(string $school = '', string $training = ''): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];

        if ($school !== '') {
            $constraints[] = $query->like('adresse.nom', '%' . $school . '%');
        }

        if ($training !== '') {
            $searchValue = '%' . $training . '%';
            $constraints[] = $query->logicalOr(
                $query->like('formation', $searchValue),
                $query->like('profession.nomMasc', $searchValue),
                $query->like('profession.nomFem', $searchValue),
                $query->like('profession.motsCles', $searchValue)
            );
        }

        if (count($constraints) > 0) {
            $query->matching($query->logicalAnd($constraints));
        }

        return $query->execute();
    }
}
