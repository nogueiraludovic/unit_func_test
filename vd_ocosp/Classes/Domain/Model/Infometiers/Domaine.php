<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Model\Infometiers;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Vd\VdOcosp\Domain\Model\Profession;

class Domaine extends AbstractEntity
{
    protected string $description = '';
    protected string $nom = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOcosp\Domain\Model\Profession>
     */
    protected ObjectStorage $profession;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addProfession(Profession $profession): Domaine
    {
        $this->profession->attach($profession);

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getProfession(): ObjectStorage
    {
        return $this->profession;
    }

    public function initializeObject(): void
    {
        $this->profession = $this->profession ?? new ObjectStorage();
    }

    public function removeProfession(Profession $profession): Domaine
    {
        $this->profession->detach($profession);

        return $this;
    }

    public function setDescription(string $description): Domaine
    {
        $this->description = $description;

        return $this;
    }

    public function setNom(string $nom): Domaine
    {
        $this->nom = $nom;

        return $this;
    }

    public function setProfession(ObjectStorage $profession): Domaine
    {
        $this->profession = $profession;

        return $this;
    }
}
