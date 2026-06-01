<?php

declare(strict_types=1);

namespace Vd\VdAlertes\Domain\Model;

use TYPO3\CMS\Core\Resource\FileReference;
use Vd\VdAlertes\DomainObject\AbstractAlert;

class HomeAlert extends AbstractAlert
{
    protected int $color = 0;
    protected ?FileReference $image = null;

    public function getColor(): int
    {
        return $this->color;
    }

    public function getImage(): ?FileReference
    {
        return $this->image;
    }
}
