<?php

declare(strict_types=1);

namespace Vd\VdMunicipalities\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class District extends AbstractEntity
{
    protected int $idDistrict = 0;
    protected string $name = '';

    public function getIdDistrict(): int
    {
        return $this->idDistrict;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setIdDistrict(int $idDistrict): District
    {
        $this->idDistrict = $idDistrict;

        return $this;
    }

    public function setName(string $name): District
    {
        $this->name = $name;

        return $this;
    }
}
