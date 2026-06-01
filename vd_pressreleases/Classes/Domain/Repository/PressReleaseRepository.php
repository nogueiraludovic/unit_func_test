<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use Vd\VdPressreleases\Domain\Model\PressRelease;

class PressReleaseRepository extends Repository
{
    public function findByUid(
        $uid,
        bool $ignoreEnableFields = false,
        bool $respectStoragePage = true,
        bool $respectSysLanguage = true
    ): ?PressRelease {
        $query = $this->createQuery();
        $query
            ->getQuerySettings()
            ->setIgnoreEnableFields($ignoreEnableFields)
            ->setRespectStoragePage($respectStoragePage)
            ->setRespectSysLanguage($respectSysLanguage);

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $query
            ->matching($query->equals('uid', $uid))
            ->execute()
            ->getFirst();
    }
}
