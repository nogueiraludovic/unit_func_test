<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Domain\Repository;

use ReflectionObject;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Vd\VdPrestations\Domain\Model\Prestation;

use function count;
use function method_exists;
use function ucfirst;

class PrestationRepository extends AbstractRepository
{
    public function findByDomainAndTheme(string $domain = '', string $theme = '')
    {
        $query = $this->createQuery();
        $query->setOrderings([
            'title' => QueryInterface::ORDER_ASCENDING
        ]);

        $constraints = [];

        if ($domain !== '') {
            $constraints[] = $query->equals('domain_id', $domain);
        }

        if ($theme !== '') {
            $constraints[] = $query->equals('theme_id', $theme);
        }

        if (count($constraints) > 0) {
            return $query
                ->matching($query->logicalAnd($constraints))
                ->execute();
        }

        return [];
    }

    public function findByExternalId(string $externalId): ?Prestation
    {
        $this->setDefaultQuerySettings($this->getQuerySettings());

        $query = $this->createQuery();

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $query
            ->matching($query->equals('external_id', $externalId))
            ->setOrderings([
                'external_id' => QueryInterface::ORDER_ASCENDING
            ])
            ->execute()
            ->getFirst();
    }

    public function findByExternalIds(string $externalIds): array
    {
        $query = $this->createQuery();
        $query->setOrderings([
            'title' => QueryInterface::ORDER_ASCENDING
        ]);

        $constraints = [];

        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $externalIds = GeneralUtility::trimExplode(',', $externalIds, true);

        foreach ($externalIds as $externalId) {
            $constraints[] = $query->equals('externalId', $externalId);
        }

        if (count($constraints) > 0) {
            $query = $query->matching($query->logicalOr($constraints));
        }

        return $query
            ->execute()
            ->toArray();
    }

    public function findByTitle(string $title): ?Prestation
    {
        $query = $this->createQuery();

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $query
            ->matching($query->equals('title', $title))
            ->setOrderings([
                'title' => QueryInterface::ORDER_ASCENDING
            ])
            ->execute()
            ->getFirst();
    }

    public function merge(Prestation $newPrestation, Prestation $existingPrestation): void
    {
        $properties = (new ReflectionObject($newPrestation))->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);

            if ($property->class === Prestation::class) {
                $name = ucfirst($property->getName());

                $getter = 'get' . $name;
                $setter = 'set' . $name;

                if (method_exists(Prestation::class, $getter) === true) {
                    $value = $newPrestation->{$getter}();
                    $existingPrestation->{$setter}($value);
                }
            }
        }

        $this->update($existingPrestation);
    }
}
