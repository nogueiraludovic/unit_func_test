<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Sale extends AbstractEntity
{
    protected string $address = '';
    protected string $city = '';
    protected string $contact = '';
    protected string $discount = '';
    protected string $email = '';
    protected string $exposure = '';
    protected ?Office $mainOffice = null;
    protected string $name = '';
    protected string $observations = '';
    protected string $parkInformation = '';
    protected string $phone = '';
    protected string $poBox = '';
    protected string $pubDate = '';
    protected string $room = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOjvencheres\Domain\Model\SaleCategory>
     */
    protected ObjectStorage $saleCategories;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOjvencheres\Domain\Model\SaleCondition>
     */
    protected ObjectStorage $saleConditions;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOjvencheres\Domain\Model\SaleDate>
     */
    protected ObjectStorage $saleDate;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOjvencheres\Domain\Model\Item>
     */
    protected ObjectStorage $saleItems;
    protected ?Office $secondaryOffice = null;
    protected int $status = 0;
    protected int $tstamp = 0;
    protected string $zipCode = '';

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addSaleCategory(SaleCategory $saleCategory): Sale
    {
        $this->saleCategories->attach($saleCategory);

        return $this;
    }

    public function addSaleCondition(SaleCondition $saleCondition): Sale
    {
        $this->saleConditions->attach($saleCondition);

        return $this;
    }

    public function addSaleDate(SaleDate $saleDate): Sale
    {
        $this->saleDate->attach($saleDate);

        return $this;
    }

    public function addSaleItem(Item $saleItem): Sale
    {
        $this->saleItems->attach($saleItem);

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getContact(): string
    {
        return $this->contact;
    }

    public function getDiscount(): string
    {
        return $this->discount;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getExposure(): string
    {
        return $this->exposure;
    }

    public function getFirstSaleCategory(): ?SaleCategory
    {
        $this->saleCategories->rewind();

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->saleCategories->current();
    }

    public function getFirstSaleItem(): ?Item
    {
        $this->saleItems->rewind();

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->saleItems->current();
    }

    public function getMainOffice(): ?Office
    {
        return $this->mainOffice;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getObservations(): string
    {
        return $this->observations;
    }

    public function getParkInformation(): string
    {
        return $this->parkInformation;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getPoBox(): string
    {
        return $this->poBox;
    }

    public function getPubDate(): string
    {
        return $this->pubDate;
    }

    public function getRoom(): string
    {
        return $this->room;
    }

    public function getSaleCategories(): ObjectStorage
    {
        return $this->saleCategories;
    }

    public function getSaleConditions(): ObjectStorage
    {
        return $this->saleConditions;
    }

    public function getSaleDate(): ObjectStorage
    {
        $now = (new DateTime('today midnight'));
        $saleDates = clone $this->saleDate;

        foreach ($saleDates as $saleDate) {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            if ($now <= $saleDate->getSaleDate()) {
                continue;
            }

            $this->saleDate->detach($saleDate);
        }

        return $this->saleDate;
    }

    public function getSaleItems(): ObjectStorage
    {
        return $this->saleItems;
    }

    public function getSecondaryOffice(): ?Office
    {
        return $this->secondaryOffice;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getTstamp(): int
    {
        return $this->tstamp;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function initializeObject(): void
    {
        $this->saleCategories = $this->saleCategories ?? new ObjectStorage();
        $this->saleConditions = $this->saleConditions ?? new ObjectStorage();
        $this->saleDate = $this->saleDate ?? new ObjectStorage();
        $this->saleItems = $this->saleItems ?? new ObjectStorage();
    }

    public function removeSaleCategory(SaleCategory $saleCategory): Sale
    {
        $this->saleCategories->detach($saleCategory);

        return $this;
    }

    public function removeSaleCondition(SaleCondition $saleCondition): Sale
    {
        $this->saleConditions->detach($saleCondition);

        return $this;
    }

    public function removeSaleDate(SaleDate $saleDate): Sale
    {
        $this->saleDate->detach($saleDate);

        return $this;
    }

    public function removeSaleItem(Item $saleItem): Sale
    {
        $this->saleItems->detach($saleItem);

        return $this;
    }

    public function setAddress(string $address): Sale
    {
        $this->address = $address;

        return $this;
    }

    public function setCity(string $city): Sale
    {
        $this->city = $city;

        return $this;
    }

    public function setContact(string $contact): Sale
    {
        $this->contact = $contact;

        return $this;
    }

    public function setDiscount(string $discount): Sale
    {
        $this->discount = $discount;

        return $this;
    }

    public function setEmail(string $email): Sale
    {
        $this->email = $email;

        return $this;
    }

    public function setExposure(string $exposure): Sale
    {
        $this->exposure = $exposure;

        return $this;
    }

    public function setMainOffice(Office $mainOffice): Sale
    {
        $this->mainOffice = $mainOffice;

        return $this;
    }

    public function setName(string $name): Sale
    {
        $this->name = $name;

        return $this;
    }

    public function setObservations(string $observations): Sale
    {
        $this->observations = $observations;

        return $this;
    }

    public function setParkInformation(string $parkInformation): Sale
    {
        $this->parkInformation = $parkInformation;

        return $this;
    }

    public function setPhone(string $phone): Sale
    {
        $this->phone = $phone;

        return $this;
    }

    public function setPoBox(string $poBox): Sale
    {
        $this->poBox = $poBox;

        return $this;
    }

    public function setPubDate(string $pubDate): Sale
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    public function setRoom(string $room): Sale
    {
        $this->room = $room;

        return $this;
    }

    public function setSaleCategories(ObjectStorage $saleCategories): Sale
    {
        $this->saleCategories = $saleCategories;

        return $this;
    }

    public function setSaleConditions(ObjectStorage $saleConditions): Sale
    {
        $this->saleConditions = $saleConditions;

        return $this;
    }

    public function setSaleDate(ObjectStorage $saleDate): Sale
    {
        $this->saleDate = $saleDate;

        return $this;
    }

    public function setSaleItems(ObjectStorage $saleItems): Sale
    {
        $this->saleItems = $saleItems;

        return $this;
    }

    public function setSecondaryOffice(Office $secondaryOffice): Sale
    {
        $this->secondaryOffice = $secondaryOffice;

        return $this;
    }

    public function setStatus(int $status): Sale
    {
        $this->status = $status;

        return $this;
    }

    public function setTstamp(int $tstamp): Sale
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    public function setZipCode(string $zipCode): Sale
    {
        $this->zipCode = $zipCode;

        return $this;
    }
}
