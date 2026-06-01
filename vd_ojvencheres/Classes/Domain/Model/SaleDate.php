<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class SaleDate extends AbstractEntity
{
    protected string $hour = '';
    protected ?Sale $sale = null;
    protected ?DateTime $saleDate = null;

    public function getHour(): string
    {
        return $this->hour;
    }

    public function getSale(): ?Sale
    {
        return $this->sale;
    }

    public function getSaleDate(): ?DateTime
    {
        return $this->saleDate;
    }

    public function setHour(string $hour): SaleDate
    {
        $this->hour = $hour;

        return $this;
    }

    public function setSale(Sale $sale): SaleDate
    {
        $this->sale = $sale;

        return $this;
    }

    public function setSaleDate(DateTime $saleDate): SaleDate
    {
        $this->saleDate = $saleDate;

        return $this;
    }
}
