<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class ItemCondition extends AbstractEntity
{
    protected string $description = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOjvencheres\Domain\Model\ItemCategory>
     */
    protected ObjectStorage $itemCategories;
    protected string $name = '';

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addItemCategory(ItemCategory $itemCategory): ItemCondition
    {
        $this->itemCategories->attach($itemCategory);

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getItemCategories(): ObjectStorage
    {
        return $this->itemCategories;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function initializeObject(): void
    {
        $this->itemCategories = $this->itemCategories ?? new ObjectStorage();
    }

    public function removeItemCategory(ItemCategory $itemCategory): ItemCondition
    {
        $this->itemCategories->detach($itemCategory);

        return $this;
    }

    public function setDescription(string $description): ItemCondition
    {
        $this->description = $description;

        return $this;
    }

    public function setItemCategories(ObjectStorage $itemCategories): ItemCondition
    {
        $this->itemCategories = $itemCategories;

        return $this;
    }

    public function setName(string $name): ItemCondition
    {
        $this->name = $name;

        return $this;
    }
}
