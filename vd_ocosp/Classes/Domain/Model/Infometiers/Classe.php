<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Model\Infometiers;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Classe extends AbstractEntity
{
    protected string $description = '';
    protected string $nom = '';

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setDescription($description): Classe
    {
        $this->description = $description;

        return $this;
    }

    public function setNom($nom): Classe
    {
        $this->nom = $nom;

        return $this;
    }
}
