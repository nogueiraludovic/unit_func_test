<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Inscription extends AbstractEntity
{
    protected ?Adresse $adresse = null;
    protected string $dateExamen = '';
    protected string $delaiInscription = '';
    protected ?Diplome $diplome = null;
    protected ?Domaine $domaine = null;
    protected string $formation = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOcosp\Domain\Model\Profession>
     */
    protected ObjectStorage $profession;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $urlExad;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addProfession(Profession $profession): Inscription
    {
        $this->profession->attach($profession);

        return $this;
    }

    public function addUrlExad(FileReference $urlExad): Inscription
    {
        $this->urlExad->attach($urlExad);

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function getDateExamen(): string
    {
        return $this->dateExamen;
    }

    public function getDelaiInscription(): string
    {
        return $this->delaiInscription;
    }

    public function getDiplome(): ?Diplome
    {
        return $this->diplome;
    }

    public function getDomaine(): ?Domaine
    {
        return $this->domaine;
    }

    public function getFormation(): string
    {
        return $this->formation;
    }

    public function getProfession(): ObjectStorage
    {
        return $this->profession;
    }

    public function getUrlExad(): ObjectStorage
    {
        return $this->urlExad;
    }

    public function initializeObject(): void
    {
        $this->profession = $this->profession ?? new ObjectStorage();
        $this->urlExad = $this->profession ?? new ObjectStorage();
    }

    public function removeProfession(Profession $profession): Inscription
    {
        $this->profession->detach($profession);

        return $this;
    }

    public function removeUrlExad(FileReference $urlExad): Inscription
    {
        $this->urlExad->detach($urlExad);

        return $this;
    }

    public function setAdresse(Adresse $adresse): Inscription
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function setDateExamen(string $dateExamen): Inscription
    {
        $this->dateExamen = $dateExamen;

        return $this;
    }

    public function setDelaiInscription(string $delaiInscription): Inscription
    {
        $this->delaiInscription = $delaiInscription;

        return $this;
    }

    public function setDiplome(Diplome $diplome): Inscription
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function setDomaine(Domaine $domaine): Inscription
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function setFormation(string $formation): Inscription
    {
        $this->formation = $formation;

        return $this;
    }

    public function setProfession(ObjectStorage $profession): Inscription
    {
        $this->profession = $profession;

        return $this;
    }

    public function setUrlExad(ObjectStorage $urlExad): Inscription
    {
        $this->urlExad = $urlExad;

        return $this;
    }
}
