<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Lot extends AbstractEntity
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOjvencheres\Domain\Model\Item>
     */
    protected ObjectStorage $items;
    protected string $name = '';
    protected ?Sale $sale = null;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addItem(Item $item): Lot
    {
        $this->items->attach($item);

        return $this;
    }

    public function getItems(): ObjectStorage
    {
        return $this->items;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSale(): ?Sale
    {
        return $this->sale;
    }

    public function initializeObject(): void
    {
        $this->items = $this->items ?? new ObjectStorage();
    }

    public function removeItem(Item $item): Lot
    {
        $this->items->detach($item);

        return $this;
    }

    public function setItems(ObjectStorage $items): Lot
    {
        $this->items = $items;

        return $this;
    }

    public function setName(string $name): Lot
    {
        $this->name = $name;

        return $this;
    }

    public function setSale(Sale $sale): Lot
    {
        $this->sale = $sale;

        return $this;
    }
}
