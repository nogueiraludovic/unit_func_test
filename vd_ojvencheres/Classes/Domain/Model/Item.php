<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

use function array_filter;
use function implode;
use function trim;

class Item extends AbstractEntity
{
    protected string $address = '';
    protected string $brand = '';
    protected bool $canceled = false;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $chargeState;
    protected string $city = '';
    protected string $color = '';
    protected string $conditions = '';
    protected string $description = '';
    protected string $district = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $documents;
    protected string $expertiseDate = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $expertiseReport;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOjvencheres\Domain\Model\ItemCategory>
     */
    protected ObjectStorage $itemCategories;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOjvencheres\Domain\Model\ItemCondition>
     */
    protected ObjectStorage $itemConditions;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOjvencheres\Domain\Model\ItemCategory>
     */
    protected ObjectStorage $itemSubCategories;
    protected string $kilometer = '';
    protected string $model = '';
    protected string $municipality = '';
    protected string $name = '';
    protected string $observations = '';
    protected string $parcelNumber = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $pictures;
    protected string $price = '';
    protected string $roomsNumber = '';
    protected ?Sale $sale = null;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $saleConditionsFile;
    protected string $search = '';
    protected string $surface = '';
    protected int $tstamp = 0;
    protected int $year = 0;
    protected string $zipCode = '';

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addChargeState(FileReference $chargeState): Item
    {
        $this->chargeState->attach($chargeState);

        return $this;
    }

    public function addDocument(FileReference $document): Item
    {
        $this->documents->attach($document);

        return $this;
    }

    public function addExpertiseReport(FileReference $expertiseReport): Item
    {
        $this->expertiseReport->attach($expertiseReport);

        return $this;
    }

    public function addItemCategory(ItemCategory $itemCategory): Item
    {
        $this->itemCategories->attach($itemCategory);

        return $this;
    }

    public function addItemCondition(ItemCondition $itemCondition): Item
    {
        $this->itemConditions->attach($itemCondition);

        return $this;
    }

    public function addItemSubCategory(ItemCategory $itemSubCategory): Item
    {
        $this->itemSubCategories->attach($itemSubCategory);

        return $this;
    }

    public function addPicture(FileReference $picture): Item
    {
        $this->pictures->attach($picture);

        return $this;
    }

    public function addSaleConditionsFile(FileReference $saleConditionsFile): Item
    {
        $this->saleConditionsFile->attach($saleConditionsFile);

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getAllDocuments(): ObjectStorage
    {
        $allObjects = new ObjectStorage();
        $allObjects->addAll($this->saleConditionsFile);
        $allObjects->addAll($this->chargeState);
        $allObjects->addAll($this->expertiseReport);
        $allObjects->addAll($this->documents);

        return $allObjects;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getCanceled(): bool
    {
        return $this->canceled;
    }

    public function getChargeState(): ObjectStorage
    {
        return $this->chargeState;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getConditions(): string
    {
        return $this->conditions;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDistrict(): string
    {
        return $this->district;
    }

    public function getDocuments(): ObjectStorage
    {
        return $this->documents;
    }

    public function getExpertiseDate(): string
    {
        return $this->expertiseDate;
    }

    public function getExpertiseReport(): ObjectStorage
    {
        return $this->expertiseReport;
    }

    public function getFirstItemCategory(): ?ItemCategory
    {
        $this->itemCategories->rewind();

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->itemCategories->current();
    }

    public function getFirstItemSubCategory(): ?ItemCategory
    {
        $this->itemSubCategories->rewind();

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->itemSubCategories->current();
    }

    public function getItemCategories(): ObjectStorage
    {
        return $this->itemCategories;
    }

    public function getItemConditions(): ObjectStorage
    {
        return $this->itemConditions;
    }

    public function getItemSubCategories(): ObjectStorage
    {
        return $this->itemSubCategories;
    }

    public function getKilometer(): string
    {
        return $this->kilometer;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getMunicipality(): string
    {
        return $this->municipality;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getObservations(): string
    {
        return $this->observations;
    }

    public function getParcelNumber(): string
    {
        return $this->parcelNumber;
    }

    public function getPictures(): ObjectStorage
    {
        return $this->pictures;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getRoomsNumber(): string
    {
        return $this->roomsNumber;
    }

    public function getSale(): ?Sale
    {
        return $this->sale;
    }

    public function getSaleConditionsFile(): ObjectStorage
    {
        return $this->saleConditionsFile;
    }

    public function getSearch(): string
    {
        return $this->search;
    }

    public function getSurface(): string
    {
        return $this->surface;
    }

    public function getTitle(): string
    {
        $this->itemCategories->rewind();
        $this->itemSubCategories->rewind();

        $firstCategory = $this->itemCategories->current();
        $firstSubCategory = $this->itemSubCategories->current();

        if ($firstSubCategory !== null) {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            $parts[] = $firstSubCategory->getName();
        }

        if ($firstCategory->getUid() === 3) {
            if ($this->roomsNumber !== '') {
                $roomsNumber = (float)$this->roomsNumber;

                $parts[] = $roomsNumber . ($roomsNumber > 1 ? ' pièces' : ' pièce');
            }

            $parts[] = trim($this->zipCode . ' ' . $this->city);

            return implode(' - ', array_filter($parts));
        }

        if ($firstCategory->getUid() === 4) {
            $parts[] = trim($this->brand . ' ' . $this->model);
            $parts[] = $this->color;

            return implode(' - ', array_filter($parts));
        }

        $parts[] = $this->name;

        return implode(' - ', array_filter($parts));
    }

    public function getTitleWithCategory(): string
    {
        if ($this->sale === null) {
            return $this->getTitle();
        }

        $this->sale->getSaleCategories()->rewind();

        $firstSaleCategory = $this->sale->getSaleCategories()->current();

        if (($firstSaleCategory instanceof SaleCategory) === true) {
            return $this->getTitle() . ' - ' . $firstSaleCategory->getName();
        }

        return $this->getTitle();
    }

    public function getTstamp(): int
    {
        return $this->tstamp;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function initializeObject(): void
    {
        $this->chargeState = $this->chargeState ?? new ObjectStorage();
        $this->documents = $this->documents ?? new ObjectStorage();
        $this->expertiseReport = $this->expertiseReport ?? new ObjectStorage();
        $this->itemCategories = $this->itemCategories ?? new ObjectStorage();
        $this->itemConditions = $this->itemConditions ?? new ObjectStorage();
        $this->itemSubCategories = $this->itemSubCategories ?? new ObjectStorage();
        $this->pictures = $this->pictures ?? new ObjectStorage();
        $this->saleConditionsFile = $this->saleConditionsFile ?? new ObjectStorage();
    }

    public function isCanceled(): bool
    {
        return $this->canceled;
    }

    public function removeChargeState(FileReference $chargeState): Item
    {
        $this->chargeState->detach($chargeState);

        return $this;
    }

    public function removeDocument(FileReference $document): Item
    {
        $this->documents->detach($document);

        return $this;
    }

    public function removeExpertiseReport(FileReference $expertiseReport): Item
    {
        $this->expertiseReport->detach($expertiseReport);

        return $this;
    }

    public function removeItemCategory(ItemCategory $itemCategory): Item
    {
        $this->itemCategories->detach($itemCategory);

        return $this;
    }

    public function removeItemCondition(ItemCondition $itemCondition): Item
    {
        $this->itemConditions->detach($itemCondition);

        return $this;
    }

    public function removeItemSubCategory(ItemCategory $itemSubCategory): Item
    {
        $this->itemSubCategories->detach($itemSubCategory);

        return $this;
    }

    public function removePicture(FileReference $picture): Item
    {
        $this->pictures->detach($picture);

        return $this;
    }

    public function removeSaleConditionsFile(FileReference $saleConditionsFile): Item
    {
        $this->saleConditionsFile->detach($saleConditionsFile);

        return $this;
    }

    public function setAddress(string $address): Item
    {
        $this->address = $address;

        return $this;
    }

    public function setBrand(string $brand): Item
    {
        $this->brand = $brand;

        return $this;
    }

    public function setCanceled(bool $canceled): Item
    {
        $this->canceled = $canceled;

        return $this;
    }

    public function setChargeState(ObjectStorage $chargeState): Item
    {
        $this->chargeState = $chargeState;

        return $this;
    }

    public function setCity(string $city): Item
    {
        $this->city = $city;

        return $this;
    }

    public function setColor(string $color): Item
    {
        $this->color = $color;

        return $this;
    }

    public function setConditions(string $conditions): Item
    {
        $this->conditions = $conditions;

        return $this;
    }

    public function setDescription(string $description): Item
    {
        $this->description = $description;

        return $this;
    }

    public function setDistrict(string $district): Item
    {
        $this->district = $district;

        return $this;
    }

    public function setDocuments(ObjectStorage $documents): Item
    {
        $this->documents = $documents;

        return $this;
    }

    public function setExpertiseDate(string $expertiseDate): Item
    {
        $this->expertiseDate = $expertiseDate;

        return $this;
    }

    public function setExpertiseReport(ObjectStorage $expertiseReport): Item
    {
        $this->expertiseReport = $expertiseReport;

        return $this;
    }

    public function setItemCategories(ObjectStorage $itemCategories): Item
    {
        $this->itemCategories = $itemCategories;

        return $this;
    }

    public function setItemConditions(ObjectStorage $itemConditions): Item
    {
        $this->itemConditions = $itemConditions;

        return $this;
    }

    public function setItemSubCategories(ObjectStorage $itemSubCategories): Item
    {
        $this->itemSubCategories = $itemSubCategories;

        return $this;
    }

    public function setKilometer(string $kilometer): Item
    {
        $this->kilometer = $kilometer;

        return $this;
    }

    public function setModel(string $model): Item
    {
        $this->model = $model;

        return $this;
    }

    public function setMunicipality(string $municipality): Item
    {
        $this->municipality = $municipality;

        return $this;
    }

    public function setName(string $name): Item
    {
        $this->name = $name;

        return $this;
    }

    public function setObservations(string $observations): Item
    {
        $this->observations = $observations;

        return $this;
    }

    public function setParcelNumber(string $parcelNumber): Item
    {
        $this->parcelNumber = $parcelNumber;

        return $this;
    }

    public function setPictures(ObjectStorage $pictures): Item
    {
        $this->pictures = $pictures;

        return $this;
    }

    public function setPrice(string $price): Item
    {
        $this->price = $price;

        return $this;
    }

    public function setRoomsNumber(string $roomsNumber): Item
    {
        $this->roomsNumber = $roomsNumber;

        return $this;
    }

    public function setSale(Sale $sale): Item
    {
        $this->sale = $sale;

        return $this;
    }

    public function setSaleConditionsFile(ObjectStorage $saleConditionsFile): Item
    {
        $this->saleConditionsFile = $saleConditionsFile;

        return $this;
    }

    public function setSearch(string $search): Item
    {
        $this->search = $search;

        return $this;
    }

    public function setSurface(string $surface): Item
    {
        $this->surface = $surface;

        return $this;
    }

    public function setTstamp(int $tstamp): Item
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    public function setYear(int $year): Item
    {
        $this->year = $year;

        return $this;
    }

    public function setZipCode(string $zipCode): Item
    {
        $this->zipCode = $zipCode;

        return $this;
    }
}
