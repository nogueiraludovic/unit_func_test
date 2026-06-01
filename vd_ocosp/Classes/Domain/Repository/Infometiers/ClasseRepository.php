<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Repository\Infometiers;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class ClasseRepository extends Repository
{
    protected $defaultOrderings = [
        'uid' => QueryInterface::ORDER_ASCENDING
    ];
}
