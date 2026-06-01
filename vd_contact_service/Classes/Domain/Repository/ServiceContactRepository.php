<?php

declare(strict_types=1);

namespace Vd\VdContactService\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

class ServiceContactRepository extends Repository
{
    public function initializeObject(): void
    {
        $this->setDefaultQuerySettings(
            GeneralUtility::makeInstance(Typo3QuerySettings::class)->setRespectStoragePage(false)
        );
    }
}
