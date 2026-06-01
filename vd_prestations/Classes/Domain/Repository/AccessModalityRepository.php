<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Domain\Repository;

use Vd\VdPrestations\Domain\Model\AccessModality;

use function md5;
use function serialize;

class AccessModalityRepository extends AbstractRepository
{
    public function add($object): void
    {
        /** @var AccessModality $object */
        $object->setHash(self::generateHash($object));

        parent::add($object);
    }

    public function findByHash(string $hash): ?AccessModality
    {
        $this->setDefaultQuerySettings($this->getQuerySettings());

        $query = $this->createQuery();

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $query
            ->matching($query->equals('hash', $hash))
            ->setLimit(1)
            ->execute()
            ->getFirst();
    }

    public static function generateHash(AccessModality $accessModality): string
    {
        return md5(serialize([
            $accessModality->getType(),
            $accessModality->getHowto(),
            $accessModality->getUrl(),
            $accessModality->getHash(),
            $accessModality->getAdditionalInformations(),
            $accessModality->getAverageDelay(),
            $accessModality->getCost(),
            $accessModality->getRequiredDocuments(),
            $accessModality->getSecurityLevel()
        ]));
    }
}
