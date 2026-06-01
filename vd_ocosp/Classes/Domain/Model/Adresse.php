<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Adresse extends AbstractEntity
{
    protected string $adresse = '';
    protected string $adresse2 = '';
    protected string $codePostal = '';
    protected bool $delais = false;
    protected string $nom = '';
    protected string $siteWeb = '';
    protected ?DateTime $tstamp = null;
    protected string $typeAdresse = '';
    protected string $ville = '';

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function getAdresse2(): string
    {
        return $this->adresse2;
    }

    public function getCodePostal(): string
    {
        return $this->codePostal;
    }

    public function getDelais(): bool
    {
        return $this->delais;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getSiteWeb(): string
    {
        return $this->siteWeb;
    }

    public function getTstamp(): ?DateTime
    {
        return $this->tstamp;
    }

    public function getTypeAdresse(): string
    {
        return $this->typeAdresse;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    public function setAdresse(string $adresse): Adresse
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function setAdresse2(string $adresse2): Adresse
    {
        $this->adresse2 = $adresse2;

        return $this;
    }

    public function setCodePostal(string $codePostal): Adresse
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function setDelais(bool $delais): Adresse
    {
        $this->delais = $delais;

        return $this;
    }

    public function setNom(string $nom): Adresse
    {
        $this->nom = $nom;

        return $this;
    }

    public function setSiteWeb(string $siteWeb): Adresse
    {
        $this->siteWeb = $siteWeb;

        return $this;
    }

    public function setTstamp(Datetime $tstamp): Adresse
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    public function setTypeAdresse(string $typeAdresse): Adresse
    {
        $this->typeAdresse = $typeAdresse;

        return $this;
    }

    public function setVille(string $ville): Adresse
    {
        $this->ville = $ville;

        return $this;
    }
}
