<?php

declare(strict_types=1);

namespace Vd\VdAlertes\Domain\Model;

use Vd\VdAlertes\DomainObject\AbstractAlert;

class ServiceAlert extends AbstractAlert
{
    protected int $depth = 0;

    public function getDepth(): int
    {
        return $this->depth;
    }
}
