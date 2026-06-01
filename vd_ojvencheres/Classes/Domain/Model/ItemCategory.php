<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class ItemCategory extends AbstractEntity
{
    protected string $keywords = '';
    protected string $name = '';
    protected int $parent = 0;
    protected bool $selectable = false;

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParent(): int
    {
        return $this->parent;
    }

    public function getSelectable(): bool
    {
        return $this->selectable;
    }

    public function isSelectable(): bool
    {
        return $this->selectable;
    }

    public function setKeywords(string $keywords): ItemCategory
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function setName(string $name): ItemCategory
    {
        $this->name = $name;

        return $this;
    }

    public function setParent(int $parent): ItemCategory
    {
        $this->parent = $parent;

        return $this;
    }

    public function setSelectable(bool $selectable): ItemCategory
    {
        $this->selectable = $selectable;

        return $this;
    }
}
