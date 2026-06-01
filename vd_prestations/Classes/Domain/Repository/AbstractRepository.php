<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

abstract class AbstractRepository extends Repository
{
    public function initializeObject(): void
    {
        $this->setDefaultQuerySettings(
            GeneralUtility::makeInstance(Typo3QuerySettings::class)->setRespectStoragePage(false)
        );
    }

    protected function getQuerySettings(): Typo3QuerySettings
    {
        return GeneralUtility::makeInstance(Typo3QuerySettings::class)
            ->setEnableFieldsToBeIgnored([
                'disabled',
                'hidden'
            ])
            ->setIgnoreEnableFields(true)
            ->setRespectStoragePage(false);
    }
}
