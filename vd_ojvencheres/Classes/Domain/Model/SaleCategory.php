<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class SaleCategory extends AbstractEntity
{
    protected string $name = '';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): SaleCategory
    {
        $this->name = $name;

        return $this;
    }
}
