<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Office extends AbstractEntity
{
    protected string $address = '';
    protected string $city = '';
    protected string $name = '';
    protected string $pageId = '';
    protected string $postalCode = '';
    protected string $zipCode = '';

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPageId(): string
    {
        return $this->pageId;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setAddress(string $address): Office
    {
        $this->address = $address;

        return $this;
    }

    public function setCity(string $city): Office
    {
        $this->city = $city;

        return $this;
    }

    public function setName(string $name): Office
    {
        $this->name = $name;

        return $this;
    }

    public function setPageId(string $pageId): Office
    {
        $this->pageId = $pageId;

        return $this;
    }

    public function setPostalCode(string $postalCode): Office
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function setZipCode(string $zipCode): Office
    {
        $this->zipCode = $zipCode;

        return $this;
    }
}
