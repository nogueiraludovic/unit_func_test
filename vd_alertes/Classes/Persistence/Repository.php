<?php

declare(strict_types=1);

namespace Vd\VdAlertes\Persistence;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository as ExtbaseRepository;

class Repository extends ExtbaseRepository
{
    protected $defaultOrderings = [
        'crdate' => QueryInterface::ORDER_DESCENDING
    ];

    public function initializeObject(): void
    {
        $this->setDefaultQuerySettings(
            GeneralUtility::makeInstance(Typo3QuerySettings::class)->setRespectStoragePage(false)
        );
    }
}
