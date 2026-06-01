<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Model\Dto;

class ItemDemand extends AbstractDemand
{
    protected int $category = -1;
    protected string $searchWord = '';
    protected int $subCategory = -1;

    public function __construct(int $category = -1, string $searchWord = '', int $subCategory = -1)
    {
        $this->category = $category;
        $this->searchWord = $searchWord;
        $this->subCategory = $subCategory;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function getSearchWord(): string
    {
        return $this->searchWord;
    }

    public function getSubCategory(): int
    {
        return $this->subCategory;
    }

    public function hasSearch(): bool
    {
        return $this->category !== -1 || $this->searchWord !== '' || $this->subCategory !== -1;
    }

    public function setCategory(int $category): ItemDemand
    {
        $this->category = $category;

        return $this;
    }

    public function setSearchWord(string $searchWord): ItemDemand
    {
        $this->searchWord = $searchWord;

        return $this;
    }

    public function setSubCategory(int $subCategory): ItemDemand
    {
        $this->subCategory = $subCategory;

        return $this;
    }
}
