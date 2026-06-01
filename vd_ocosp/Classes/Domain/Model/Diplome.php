<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Diplome extends AbstractEntity
{
    protected string $diplome = '';
    protected int $sorting = 0;

    public function getDiplome(): string
    {
        return $this->diplome;
    }

    public function getSorting(): int
    {
        return $this->sorting;
    }

    public function setDiplome(string $diplome): Diplome
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function setSorting(int $sorting): Diplome
    {
        $this->sorting = $sorting;

        return $this;
    }
}
