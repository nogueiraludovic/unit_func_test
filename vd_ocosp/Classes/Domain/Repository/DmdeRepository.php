<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Vd\VdOcosp\Domain\Model\Adresse;
use Vd\VdOcosp\Domain\Model\Dmde;
use Vd\VdOcosp\Domain\Model\Profession;

use function count;

class DmdeRepository extends Repository
{
    protected $defaultOrderings = [
        'profession' => QueryInterface::ORDER_ASCENDING
    ];

    public function findAll(): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->matching($query->greaterThan('professionId.uid', 0))
            ->execute();
    }

    public function findAllWithApprenticeship(): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->matching(
                $query->logicalAnd(
                    $query->greaterThan('professionId.uid', 0),
                    $query->logicalOr(
                        $query->like('professionId.nomFem', '%AFP%'),
                        $query->like('professionId.nomFem', '%CFC%'),
                        $query->like('professionId.nomFem', '%FPA%'),
                        $query->like('professionId.nomMasc', '%AFP%'),
                        $query->like('professionId.nomMasc', '%CFC%'),
                        $query->like('professionId.nomMasc', '%FPA%')
                    )
                )
            )
            ->setOrderings([
                'domaineId.nom' => QueryInterface::ORDER_ASCENDING
            ])
            ->execute();
    }

    public function findAllWithSalary(): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->matching(
                $query->logicalOr([
                    $query->logicalNot(
                        $query->equals('salaire_horaire', '')
                    ),
                    $query->logicalNot(
                        $query->equals('salaire_mensuel', '')
                    )
                ])
            )
            ->setOrderings([
                'profession' => QueryInterface::ORDER_ASCENDING,
            ])
            ->execute();
    }

    public function findByAddress(Adresse $address): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query
            ->matching(
                $query->logicalAnd([
                    $query->contains('adresse', $address),
                    $query->greaterThan('professionId.uid', 0)
                ])
            )
            ->execute();
    }

    public function findByInterests(array $interests): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_vdocosp_dmde');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $records = $queryBuilder
            ->select('dmde.uid', 'profession', 'profession_id')
            ->from('tx_vdocosp_dmde', 'dmde')
            ->join(
                'dmde',
                'tx_vdocosp_dmde_interet_mm',
                'tx_vdocosp_dmde_interet_mm',
                $queryBuilder->expr()->eq(
                    'tx_vdocosp_dmde_interet_mm.uid_local',
                    $queryBuilder->quoteIdentifier('dmde.uid')
                )
            )
            ->join(
                'tx_vdocosp_dmde_interet_mm',
                'tx_vdocosp_interets',
                'tx_vdocosp_interets',
                $queryBuilder->expr()->eq(
                    'tx_vdocosp_dmde_interet_mm.uid_foreign',
                    $queryBuilder->quoteIdentifier('tx_vdocosp_interets.uid')
                )
            )
            ->add('groupBy', '`dmde`.`uid` HAVING COUNT(*) >=' . count($interests))
            ->where(
                $queryBuilder->expr()->in(
                    'tx_vdocosp_dmde_interet_mm.uid_foreign',
                    $queryBuilder->createNamedParameter($interests, Connection::PARAM_INT_ARRAY)
                )
            )
            ->execute()
            ->fetchAllAssociative();

        return $this->objectManager->get(DataMapper::class)->map(Dmde::class, $records) ?? [];
    }

    public function findByProfessionId(Profession $profession): array
    {
        $query = $this->createQuery();

        return $query
            ->matching($query->equals('professionId.uid', $profession->getUid()))
            ->execute()
            ->toArray();
    }

    public function findBySearch(string $search, int $domain = 0, array $ordering = [], bool $toArray = false)
    {
        $query = $this->createQuery();

        $searchValue = '%' . $search . '%';

        $constraints = [
            $query->logicalOr([
                $query->like('profession', $searchValue),
                $query->like('professionId.motsCles', $searchValue),
                $query->like('professionId.nomFem', $searchValue),
                $query->like('professionId.nomMasc', $searchValue)
            ])
        ];

        if ($domain > 0) {
            $constraints[] = $query->logicalOr([
                $query->equals('domaineId', $domain),
                $query->equals('domaineId2', $domain)
            ]);
        }

        $constraints[] = $query->greaterThan('professionId.uid', 0);

        $query->matching($query->logicalAnd($constraints));

        if (count($ordering) === 0) {
            $query->setOrderings([
                'domaineId.nom' => QueryInterface::ORDER_ASCENDING
            ]);
        } else {
            $query->setOrderings($ordering);
        }

        return $toArray === true ? $query->execute()->toArray() : $query->execute();
    }
}
