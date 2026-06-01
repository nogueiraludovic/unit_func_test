<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Interet extends AbstractEntity
{
    protected string $nom = '';

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): Interet
    {
        $this->nom = $nom;

        return $this;
    }
}
