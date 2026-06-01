<?php

declare(strict_types=1);

namespace Vd\VdMunicipalities\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Municipality extends AbstractEntity
{
    protected int $idDistrict = 0;
    protected string $localites = '';
    protected string $nameLower = '';
    protected string $nameUpper = '';
    protected string $npa = '';
    protected int $page = 0;

    public function getIdDistrict(): int
    {
        return $this->idDistrict;
    }

    public function getLocalites(): string
    {
        return $this->localites;
    }

    public function getNameLower(): string
    {
        return $this->nameLower;
    }

    public function getNameUpper(): string
    {
        return $this->nameUpper;
    }

    public function getNpa(): string
    {
        return $this->npa;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setIdDistrict(int $idDistrict): Municipality
    {
        $this->idDistrict = $idDistrict;

        return $this;
    }

    public function setLocalites(string $localites): Municipality
    {
        $this->localites = $localites;

        return $this;
    }

    public function setNameLower(string $nameLower): Municipality
    {
        $this->nameLower = $nameLower;

        return $this;
    }

    public function setNameUpper(string $nameUpper): Municipality
    {
        $this->nameUpper = $nameUpper;

        return $this;
    }

    public function setNpa(string $npa): Municipality
    {
        $this->npa = $npa;

        return $this;
    }

    public function setPage(int $page): Municipality
    {
        $this->page = $page;

        return $this;
    }
}
