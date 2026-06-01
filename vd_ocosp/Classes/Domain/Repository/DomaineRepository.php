<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class DomaineRepository extends Repository
{
    protected $defaultOrderings = [
        'nom' => QueryInterface::ORDER_ASCENDING
    ];
}
