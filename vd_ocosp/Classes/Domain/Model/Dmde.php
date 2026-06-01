<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Dmde extends AbstractEntity
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOcosp\Domain\Model\Adresse>
     */
    protected ObjectStorage $adresse;
    protected string $conditionsAdmission = '';
    protected string $conditionsAdmission2 = '';
    protected string $description = '';
    protected string $diplomeComplement = '';
    protected ?Diplome $diplomeId = null;
    protected string $diplomeNom = '';
    protected ?Domaine $domaineId = null;
    protected ?Domaine $domaineId2 = null;
    protected int $externalId = 0;
    protected string $externalLink = '';
    protected string $formation = '';
    protected string $formation2 = '';
    protected string $formationDesc = '';
    protected int $hits = 0;
    protected string $indemnites = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOcosp\Domain\Model\Interet>
     */
    protected ObjectStorage $interets;
    protected string $lienPodcast = '';
    protected string $lieu = '';
    protected string $lieu2 = '';
    protected string $profession = '';
    protected string $professionAffichage = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOcosp\Domain\Model\Profession>
     */
    protected ObjectStorage $professionId;
    protected string $remarques = '';
    protected string $remarques2 = '';
    protected string $remarquesInterne = '';
    protected ?DateTime $tstamp = null;
    protected string $videoZoom = '';

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addAdresse(Adresse $adresse): Dmde
    {
        $this->adresse->attach($adresse);

        return $this;
    }

    public function addInteret(Interet $interet): Dmde
    {
        $this->interets->attach($interet);

        return $this;
    }

    public function addProfessionId(Profession $professionId): Dmde
    {
        $this->professionId->attach($professionId);

        return $this;
    }

    public function getAdresse(): ObjectStorage
    {
        return $this->adresse;
    }

    public function getConditionsAdmission(): string
    {
        return $this->conditionsAdmission;
    }

    public function getConditionsAdmission2(): string
    {
        return $this->conditionsAdmission2;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDiplomeComplement(): string
    {
        return $this->diplomeComplement;
    }

    public function getDiplomeId(): ?Diplome
    {
        return $this->diplomeId;
    }

    public function getDiplomeNom(): string
    {
        return $this->diplomeId->getDiplome();
    }

    public function getDomaineId(): ?Domaine
    {
        return $this->domaineId;
    }

    public function getDomaineId2(): ?Domaine
    {
        return $this->domaineId2;
    }

    public function getExternalId(): int
    {
        return $this->externalId;
    }

    public function getExternalLink(): string
    {
        return $this->externalLink;
    }

    public function getFormation(): string
    {
        return $this->formation;
    }

    public function getFormation2(): string
    {
        return $this->formation2;
    }

    public function getFormationDesc(): string
    {
        return $this->formationDesc;
    }

    public function getHits(): int
    {
        return $this->hits;
    }

    public function getIndemnites(): string
    {
        return $this->indemnites;
    }

    public function getInterets(): ObjectStorage
    {
        return $this->interets;
    }

    public function getLienPodcast(): string
    {
        return $this->lienPodcast;
    }

    public function getLieu(): string
    {
        return $this->lieu;
    }

    public function getLieu2(): string
    {
        return $this->lieu2;
    }

    public function getProfession(): string
    {
        return $this->profession;
    }

    public function getProfessionAffichage(): string
    {
        if ($this->profession === '') {
            $relatedProfession = $this->getRelatedProfession();

            if ($relatedProfession !== null) {
                return $relatedProfession->getFullName();
            }
        }

        return $this->profession;
    }

    public function getProfessionId(): ObjectStorage
    {
        return $this->professionId;
    }

    public function getRelatedProfession(): ?Profession
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->professionId->current();
    }

    public function getRemarques(): string
    {
        return $this->remarques;
    }

    public function getRemarques2(): string
    {
        return $this->remarques2;
    }

    public function getRemarquesInterne(): string
    {
        return $this->remarquesInterne;
    }

    public function getTstamp(): ?DateTime
    {
        return $this->tstamp;
    }

    public function getVideoZoom(): string
    {
        return $this->videoZoom;
    }

    public function initializeObject(): void
    {
        $this->adresse = $this->adresse ?? new ObjectStorage();
        $this->interets = $this->interets ?? new ObjectStorage();
        $this->professionId = $this->professionId ?? new ObjectStorage();
    }

    public function removeAdresse(Adresse $adresse): Dmde
    {
        $this->adresse->detach($adresse);

        return $this;
    }

    public function removeInteret(Interet $interet): Dmde
    {
        $this->interets->detach($interet);

        return $this;
    }

    public function removeProfessionId(Profession $professionId): Dmde
    {
        $this->professionId->detach($professionId);

        return $this;
    }

    public function setAdresse(ObjectStorage $adresse): Dmde
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function setConditionsAdmission(string $conditionsAdmission): Dmde
    {
        $this->conditionsAdmission = $conditionsAdmission;

        return $this;
    }

    public function setConditionsAdmission2(string $conditionsAdmission2): Dmde
    {
        $this->conditionsAdmission2 = $conditionsAdmission2;

        return $this;
    }

    public function setDescription(string $description): Dmde
    {
        $this->description = $description;

        return $this;
    }

    public function setDiplomeComplement(string $diplomeComplement): Dmde
    {
        $this->diplomeComplement = $diplomeComplement;

        return $this;
    }

    public function setDiplomeId(Diplome $diplomeId): Dmde
    {
        $this->diplomeId = $diplomeId;

        return $this;
    }

    public function setDomaineId(Domaine $domaineId): Dmde
    {
        $this->domaineId = $domaineId;

        return $this;
    }

    public function setDomaineId2(Domaine $domaineId2): Dmde
    {
        $this->domaineId2 = $domaineId2;

        return $this;
    }

    public function setExternalId(int $externalId): Dmde
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function setExternalLink(string $externalLink): Dmde
    {
        $this->externalLink = $externalLink;

        return $this;
    }

    public function setFormation(string $formation): Dmde
    {
        $this->formation = $formation;

        return $this;
    }

    public function setFormation2(string $formation2): Dmde
    {
        $this->formation2 = $formation2;

        return $this;
    }

    public function setFormationDesc(string $formationDesc): Dmde
    {
        $this->formationDesc = $formationDesc;

        return $this;
    }

    public function setHits(int $hits): Dmde
    {
        $this->hits = $hits;

        return $this;
    }

    public function setIndemnites(string $indemnites): Dmde
    {
        $this->indemnites = $indemnites;

        return $this;
    }

    public function setInterets(ObjectStorage $interets): Dmde
    {
        $this->interets = $interets;

        return $this;
    }

    public function setLienPodcast(string $lienPodcast): Dmde
    {
        $this->lienPodcast = $lienPodcast;

        return $this;
    }

    public function setLieu(string $lieu): Dmde
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function setLieu2(string $lieu2): Dmde
    {
        $this->lieu2 = $lieu2;

        return $this;
    }

    public function setProfession(string $profession): Dmde
    {
        $this->profession = $profession;

        return $this;
    }

    public function setProfessionId(ObjectStorage $professionId): Dmde
    {
        $this->professionId = $professionId;

        return $this;
    }

    public function setRemarques(string $remarques): Dmde
    {
        $this->remarques = $remarques;

        return $this;
    }

    public function setRemarques2(string $remarques2): Dmde
    {
        $this->remarques2 = $remarques2;

        return $this;
    }

    public function setRemarquesInterne(string $remarquesInterne): Dmde
    {
        $this->remarquesInterne = $remarquesInterne;

        return $this;
    }

    public function setTstamp(DateTime $tstamp): Dmde
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    public function setVideoZoom(string $videoZoom): Dmde
    {
        $this->videoZoom = $videoZoom;

        return $this;
    }
}
