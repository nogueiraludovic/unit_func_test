<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Vd\VdPrestations\Utility\StringUtility;

use function usort;

class Prestation extends AbstractEntity
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdPrestations\Domain\Model\AccessModality>
     */
    protected ObjectStorage $accessModalities;
    protected string $actionClient = '';
    protected string $actionService = '';
    protected string $description = '';
    protected string $domainId = '';
    protected string $domainName = '';
    protected string $emolument = '';
    protected string $externalId = '';
    protected string $helpLink = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdPrestations\Domain\Model\Url>
     */
    protected ObjectStorage $legalReferences;
    protected string $pathSegment = '';
    protected string $prerequisites = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdPrestations\Domain\Model\Url>
     */
    protected ObjectStorage $relatedPages;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdPrestations\Domain\Model\Prestation>
     */
    protected ObjectStorage $relatedPrestations;
    protected string $result = '';
    protected string $security = '';
    protected string $serviceName = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdPrestations\Domain\Model\TargetAudience>
     */
    protected ObjectStorage $targetAudience;
    protected string $themeId = '';
    protected string $themeName = '';
    protected string $title = '';
    protected array $updateFields = [
        'title',
        'description',
        'domainName',
        'domainId',
        'themeName',
        'themeId',
        'emolument',
        'prerequisites',
        'serviceName',
        'result',
        'actionClient',
        'actionService',
        'helpLink',
        'accessModalities',
        'targetAudience',
        'relatedPages',
        'legalReferences',
        'relatedPrestations'
    ];

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addAccessModality(AccessModality $accessModality): Prestation
    {
        $this->accessModalities->attach($accessModality);

        return $this;
    }

    public function addLegalReference(Url $legalReference): Prestation
    {
        $this->legalReferences->attach($legalReference);

        return $this;
    }

    public function addRelatedPage(Url $relatedPage): Prestation
    {
        $this->relatedPages->attach($relatedPage);

        return $this;
    }

    public function addRelatedPrestation(Prestation $relatedPrestation): Prestation
    {
        $this->relatedPrestations->attach($relatedPrestation);

        return $this;
    }

    public function addTargetAudience(TargetAudience $targetAudience): Prestation
    {
        $this->targetAudience->attach($targetAudience);

        return $this;
    }

    public function getAccessModalities(): ObjectStorage
    {
        return $this->accessModalities;
    }

    public function getActionClient(): string
    {
        return $this->actionClient;
    }

    public function getActionService(): string
    {
        return $this->actionService;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDomainId(): string
    {
        return $this->domainId;
    }

    public function getDomainName(): string
    {
        return $this->domainName;
    }

    public function getEmolument(): string
    {
        return $this->emolument;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getHelpLink(): string
    {
        return $this->helpLink;
    }

    public function getLegalReferences(): ObjectStorage
    {
        return $this->legalReferences;
    }

    public function getPathSegment(): string
    {
        return $this->pathSegment;
    }

    public function getPrerequisites(): string
    {
        return $this->prerequisites;
    }

    public function getRelatedPages(): ObjectStorage
    {
        return $this->relatedPages;
    }

    public function getRelatedPrestations(): ObjectStorage
    {
        return $this->relatedPrestations;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function getSecurity(): string
    {
        return $this->security;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getSortedAccessModalities(): ObjectStorage
    {
        $items = $this->accessModalities->toArray();

        usort(
            $items,
            static function (AccessModality $a, AccessModality $b): int {
                $order = [
                    'online' => 10,
                    'mail' => 20,
                    'guichet' => 30,
                    'poste' => 40,
                    'telephone' => 50
                ];

                $aRank = $order[$a->getType()] ?? 999;
                $bRank = $order[$b->getType()] ?? 999;

                if ($aRank === $bRank) {
                    return $a->getUid() <=> $b->getUid();
                }

                return $aRank <=> $bRank;
            }
        );

        $sortedStorage = new ObjectStorage();

        foreach ($items as $item) {
            $sortedStorage->attach($item);
        }

        return $sortedStorage;
    }

    public function getTargetAudience(): ObjectStorage
    {
        return $this->targetAudience;
    }

    public function getThemeId(): string
    {
        return $this->themeId;
    }

    public function getThemeName(): string
    {
        return $this->themeName;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function initializeObject(): void
    {
        $this->accessModalities = $this->accessModalities ?? new ObjectStorage();
        $this->legalReferences = $this->legalReferences ?? new ObjectStorage();
        $this->relatedPages = $this->relatedPages ?? new ObjectStorage();
        $this->relatedPrestations = $this->relatedPrestations ?? new ObjectStorage();
        $this->targetAudience = $this->targetAudience ?? new ObjectStorage();
    }

    public function removeAccessModality(AccessModality $accessModalityToRemove): Prestation
    {
        $this->accessModalities->detach($accessModalityToRemove);

        return $this;
    }

    public function removeLegalReference(Url $legalReferenceToRemove): Prestation
    {
        $this->legalReferences->detach($legalReferenceToRemove);

        return $this;
    }

    public function removeRelatedPage(Url $relatedPageToRemove): Prestation
    {
        $this->relatedPages->detach($relatedPageToRemove);

        return $this;
    }

    public function removeRelatedPrestation(Prestation $relatedPrestationToRemove): Prestation
    {
        $this->relatedPrestations->detach($relatedPrestationToRemove);

        return $this;
    }

    public function removeTargetAudience(TargetAudience $targetAudienceToRemove): Prestation
    {
        $this->targetAudience->detach($targetAudienceToRemove);

        return $this;
    }

    public function setAccessModalities(ObjectStorage $accessModalities): Prestation
    {
        $this->accessModalities = $accessModalities;

        return $this;
    }

    public function setActionClient(string $actionClient): Prestation
    {
        $this->actionClient = $actionClient;

        return $this;
    }

    public function setActionService(string $actionService): Prestation
    {
        $this->actionService = $actionService;

        return $this;
    }

    public function setDescription(string $description): Prestation
    {
        $this->description = $description;

        return $this;
    }

    public function setDomainId(string $domainId): Prestation
    {
        $this->domainId = $domainId;

        return $this;
    }

    public function setDomainName(string $domainName): Prestation
    {
        $this->setDomainId(StringUtility::generateUniqueId($domainName));
        $this->domainName = $domainName;

        return $this;
    }

    public function setEmolument(string $emolument): Prestation
    {
        $this->emolument = $emolument;

        return $this;
    }

    public function setExternalId(string $externalId): Prestation
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function setHelpLink(string $helpLink): Prestation
    {
        $this->helpLink = $helpLink;

        return $this;
    }

    public function setLegalReferences(ObjectStorage $legalReferences): Prestation
    {
        $this->legalReferences = $legalReferences;

        return $this;
    }

    public function setPathSegment(string $pathSegment): Prestation
    {
        $this->pathSegment = $pathSegment;

        return $this;
    }

    public function setPrerequisites(string $prerequisites): Prestation
    {
        $this->prerequisites = $prerequisites;

        return $this;
    }

    public function setRelatedPages(ObjectStorage $relatedPages): Prestation
    {
        $this->relatedPages = $relatedPages;

        return $this;
    }

    public function setRelatedPrestations(ObjectStorage $relatedPrestations): Prestation
    {
        $this->relatedPrestations = $relatedPrestations;

        return $this;
    }

    public function setResult(string $result): Prestation
    {
        $this->result = $result;

        return $this;
    }

    public function setSecurity(string $security): Prestation
    {
        $this->security = $security;

        return $this;
    }

    public function setServiceName(string $serviceName): Prestation
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function setTargetAudience(ObjectStorage $targetAudience): Prestation
    {
        $this->targetAudience = $targetAudience;

        return $this;
    }

    public function setThemeId(string $themeId): Prestation
    {
        $this->themeId = $themeId;

        return $this;
    }

    public function setThemeName(string $themeName): Prestation
    {
        $this->setThemeId(StringUtility::generateUniqueId($themeName));
        $this->themeName = $themeName;

        return $this;
    }

    public function setTitle(string $title): Prestation
    {
        $this->title = $title;

        return $this;
    }
}
