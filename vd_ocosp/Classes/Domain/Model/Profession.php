<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

use function str_replace;
use function trim;

class Profession extends AbstractEntity
{
    protected string $anciennesDenominations = '';
    protected ?Dmde $dmde = null;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdOcosp\Domain\Model\Inscription>
     */
    protected ObjectStorage $inscriptions;
    protected string $keywords = '';
    protected string $motsCles = '';
    protected string $niveauCnc = '';
    protected string $nomFem = '';
    protected string $nomMasc = '';
    protected string $remarques = '';
    protected string $remarquesExt = '';
    protected ?Diplome $titreDelivre = null;
    protected ?DateTime $tstamp = null;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addProfession(Inscription $inscription): Profession
    {
        $this->inscriptions->attach($inscription);

        return $this;
    }

    public function getAnciennesDenominations(): string
    {
        return $this->anciennesDenominations;
    }

    public function getDmde(): ?Dmde
    {
        return $this->dmde;
    }

    public function getFullName(bool $sanitizeNomMasc = true): string
    {
        if ($this->nomFem === $this->nomMasc) {
            return trim($this->nomFem);
        }

        if ($sanitizeNomMasc === true) {
            $this->nomMasc = str_replace(['AFP', 'CCC', 'CFC', 'EPF', 'HES', 'ES', ' / '], '', $this->nomMasc);
        }

        return trim($this->nomMasc . ' / ' . $this->nomFem);
    }

    public function getInscriptions(): ObjectStorage
    {
        return $this->inscriptions;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function getMotsCles(): string
    {
        return $this->motsCles;
    }

    public function getNiveauCnc(): string
    {
        return $this->niveauCnc;
    }

    public function getNomFem(): string
    {
        return $this->nomFem;
    }

    public function getNomMasc(): string
    {
        return $this->nomMasc;
    }

    public function getRemarques(): string
    {
        return $this->remarques;
    }

    public function getRemarquesExt(): string
    {
        return $this->remarquesExt;
    }

    public function getTitreDelivre(): ?Diplome
    {
        return $this->titreDelivre;
    }

    public function getTstamp(): ?DateTime
    {
        return $this->tstamp;
    }

    public function initializeObject(): void
    {
        $this->inscriptions = $this->profession ?? new ObjectStorage();
    }

    public function removeProfession(Inscription $inscription): Profession
    {
        $this->inscriptions->detach($inscription);

        return $this;
    }

    public function setAnciennesDenominations(string $anciennesDenominations): Profession
    {
        $this->anciennesDenominations = $anciennesDenominations;

        return $this;
    }

    public function setDmde(Dmde $dmde): Profession
    {
        $this->dmde = $dmde;

        return $this;
    }

    public function setInscriptions(ObjectStorage $inscriptions): Profession
    {
        $this->inscriptions = $inscriptions;

        return $this;
    }

    public function setKeywords(string $keywords): Profession
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function setMotsCles(string $motsCles): Profession
    {
        $this->motsCles = $motsCles;

        return $this;
    }

    public function setNiveauCnc(string $niveauCnc): Profession
    {
        $this->niveauCnc = $niveauCnc;

        return $this;
    }

    public function setNomFem(string $nomFem): Profession
    {
        $this->nomFem = $nomFem;

        return $this;
    }

    public function setNomMasc(string $nomMasc): Profession
    {
        $this->nomMasc = $nomMasc;

        return $this;
    }

    public function setRemarques(string $remarques): Profession
    {
        $this->remarques = $remarques;

        return $this;
    }

    public function setRemarquesExt(string $remarquesExt): Profession
    {
        $this->remarquesExt = $remarquesExt;

        return $this;
    }

    public function setTitreDelivre(Diplome $titreDelivre): Profession
    {
        $this->titreDelivre = $titreDelivre;

        return $this;
    }

    public function setTstamp(DateTime $tstamp): Profession
    {
        $this->tstamp = $tstamp;

        return $this;
    }
}
