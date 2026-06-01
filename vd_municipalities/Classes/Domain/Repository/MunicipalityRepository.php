<?php

declare(strict_types=1);

namespace Vd\VdMunicipalities\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class MunicipalityRepository extends Repository
{
    protected $defaultOrderings = [
        'nameLower' => QueryInterface::ORDER_ASCENDING,
    ];

    public function findByDistrict(int $district): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->matching($query->equals('idDistrict', $district))
            ->execute();
    }

    public function initializeObject(): void
    {
        $this->setDefaultQuerySettings(
            GeneralUtility::makeInstance(Typo3QuerySettings::class)->setRespectStoragePage(false)
        );
    }

    public function search(string $term): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->matching(
                $query->logicalOr([
                    $query->like('localites', '%' . $term . '%'),
                    $query->like('nameLower', '%' . $term . '%'),
                    $query->like('npa', '%' . $term . '%')
                ])
            )
            ->execute();
    }
}
