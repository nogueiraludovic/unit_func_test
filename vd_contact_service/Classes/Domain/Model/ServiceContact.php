<?php

declare(strict_types=1);

namespace Vd\VdContactService\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class ServiceContact extends AbstractEntity
{
    protected string $accountNumber = '';
    protected string $additionalText = '';
    protected string $additionalTitle = '';
    protected string $address = '';
    protected string $emailAddress = '';
    protected int $emailDisplayType = 0;
    protected ?FileReference $image = null;
    protected string $internetFax = '';
    protected string $link = '';
    protected string $linkLabel = '';
    protected string $locality = '';
    protected string $name = '';
    protected string $personFunction = '';
    protected string $personName = '';
    protected string $postalBox = '';
    protected string $postalCode = '';
    protected ?Service $service = null;
    protected string $summary = '';
    protected string $telephone = '';
    protected string $title = '';

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function getAdditionalText(): string
    {
        return $this->additionalText;
    }

    public function getAdditionalTitle(): string
    {
        return $this->additionalTitle;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getEmailDisplayType(): int
    {
        return $this->emailDisplayType;
    }

    public function getImage(): ?FileReference
    {
        return $this->image;
    }

    public function getInternetFax(): string
    {
        return $this->internetFax;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getLinkLabel(): string
    {
        return $this->linkLabel;
    }

    public function getLocality(): string
    {
        return $this->locality;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPersonFunction(): string
    {
        return $this->personFunction;
    }

    public function getPersonName(): string
    {
        return $this->personName;
    }

    public function getPostalBox(): string
    {
        return $this->postalBox;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
