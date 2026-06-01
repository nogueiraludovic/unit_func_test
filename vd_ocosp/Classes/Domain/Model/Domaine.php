<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Domaine extends AbstractEntity
{
    protected bool $delais = false;
    protected bool $demande = false;
    protected string $nom = '';

    public function getDelais(): bool
    {
        return $this->delais;
    }

    public function getDemande(): bool
    {
        return $this->demande;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setDelais(bool $delais): Domaine
    {
        $this->delais = $delais;

        return $this;
    }

    public function setDemande(bool $demande): Domaine
    {
        $this->demande = $demande;

        return $this;
    }

    public function setNom(string $nom): Domaine
    {
        $this->nom = $nom;

        return $this;
    }
}
