<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class ProfessionRepository extends Repository
{
    protected $defaultOrderings = [
        'nomMasc' => QueryInterface::ORDER_ASCENDING
    ];

    public function findBySearch(string $keyword, string $consult, string $repealed): QueryResultInterface
    {
        $query = $this->createQuery();

        if ($keyword !== '') {
            $searchWords = GeneralUtility::trimExplode(' ', $keyword, true);

            foreach ($searchWords as $searchWord) {
                $constraints[] = $query->logicalOr([
                    $query->like('nomMasc', '%' . $searchWord . '%'),
                    $query->like('nomFem', '%' . $searchWord . '%'),
                    $query->like('motsCles', '%' . $searchWord . '%')
               ]);
            }
        }

        if ($consult === 'o') {
            $constraints[] = $query->equals('projetConsultation', true);
        } elseif ($consult === 'n') {
            $constraints[] = $query->equals('projetConsultation', false);
        }

        if ($repealed === 'o') {
            $constraints[] = $query->equals('reglementAbroge', true);
        } elseif ($repealed === 'n') {
            $constraints[] = $query->equals('reglementAbroge', false);
        }

        return $query
            ->matching($query->logicalAnd($constraints ?? []))
            ->execute();
    }

    public function findForCpaRegistration(): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->matching($query->in('dmde.diplomeId', [1, 14]))
            ->execute();
    }

    public function findForDmde(): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->matching($query->greaterThan('dmde.uid', 0))
            ->execute();
    }
}
