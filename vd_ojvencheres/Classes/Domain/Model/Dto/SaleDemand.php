<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Model\Dto;

class SaleDemand extends AbstractDemand
{
    protected int $itemCategory = -1;
    protected int $mainOffice = -1;
    protected int $saleCategory = -1;

    public function __construct(int $itemCategory = -1, int $mainOffice = -1, int $saleCategory = -1)
    {
        $this->itemCategory = $itemCategory;
        $this->mainOffice = $mainOffice;
        $this->saleCategory = $saleCategory;
    }

    public function getItemCategory(): int
    {
        return $this->itemCategory;
    }

    public function getMainOffice(): int
    {
        return $this->mainOffice;
    }

    public function getSaleCategory(): int
    {
        return $this->saleCategory;
    }

    public function hasSearch(): bool
    {
        return $this->itemCategory !== -1 || $this->mainOffice !== -1 || $this->saleCategory !== -1;
    }

    public function setItemCategory(int $itemCategory): SaleDemand
    {
        $this->itemCategory = $itemCategory;

        return $this;
    }

    public function setMainOffice(int $mainOffice): SaleDemand
    {
        $this->mainOffice = $mainOffice;

        return $this;
    }

    public function setSaleCategory(int $saleCategory): SaleDemand
    {
        $this->saleCategory = $saleCategory;

        return $this;
    }
}
