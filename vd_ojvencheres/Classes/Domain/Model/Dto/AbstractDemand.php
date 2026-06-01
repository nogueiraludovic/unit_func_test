<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

use function serialize;

abstract class AbstractDemand extends AbstractValueObject
{
    public function __toString(): string
    {
        return serialize($this);
    }

    abstract public function hasSearch(): bool;
}
