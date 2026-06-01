<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Domain\Repository;

use Vd\VdPrestations\Domain\Model\Url;

use function md5;
use function serialize;

class UrlRepository extends AbstractRepository
{
    public function findByHash(string $hash): ?Url
    {
        $this->setDefaultQuerySettings($this->getQuerySettings());

        $query = $this->createQuery();

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $query
            ->matching($query->equals('hash', $hash))
            ->execute()
            ->getFirst();
    }

    public static function generateHash(Url $object): string
    {
        return md5(serialize([
            $object->getTitle(),
            $object->getUrl()
        ]));
    }
}
