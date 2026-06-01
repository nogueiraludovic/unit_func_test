<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class SaleCondition extends AbstractEntity
{
    protected string $description = '';
    protected string $name = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOjvencheres\Domain\Model\SaleCategory>
     */
    protected ObjectStorage $saleCategories;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addSaleCategory(SaleCategory $saleCategory): SaleCondition
    {
        $this->saleCategories->attach($saleCategory);

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSaleCategories(): ObjectStorage
    {
        return $this->saleCategories;
    }

    public function initializeObject(): void
    {
        $this->saleCategories = $this->saleCategories ?? new ObjectStorage();
    }

    public function removeSaleCategory(SaleCategory $saleCategory): SaleCondition
    {
        $this->saleCategories->detach($saleCategory);

        return $this;
    }

    public function setDescription(string $description): SaleCondition
    {
        $this->description = $description;

        return $this;
    }

    public function setName(string $name): SaleCondition
    {
        $this->name = $name;

        return $this;
    }

    public function setSaleCategories(ObjectStorage $saleCategories): SaleCondition
    {
        $this->saleCategories = $saleCategories;

        return $this;
    }
}
