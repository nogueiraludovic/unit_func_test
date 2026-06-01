<?php

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
declare(strict_types=1);

namespace Vd\VdPressreleases\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class PressRelease extends AbstractEntity
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdPressreleases\Domain\Model\Link>
     */
    protected ObjectStorage $additionalContents;
    protected string $additionalText = '';
    protected bool $anonymize = true;
    protected ?DateTime $anonymizeTime = null;
    protected string $bodyText = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdPressreleases\Domain\Model\Contact>
     */
    protected ObjectStorage $contacts;
    protected ?DateTime $dateTime = null;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $files;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $forcedpdfFile;
    protected bool $hidden = false;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $images;
    protected string $keywords = '';
    protected string $partnerSourceInfos = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdPressreleases\Domain\Model\PartnerSource>
     */
    protected ObjectStorage $partnerSources;
    protected string $pathSegment = '';
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdPressreleases\Domain\Model\Link>
     */
    protected ObjectStorage $publidocFiles;
    protected string $signature = '';
    protected string $source = '';
    protected int $sourceId = 0;
    protected string $subtitle = '';
    protected string $summary = '';
    protected string $title = '';
    protected ?PressReleaseType $type = null;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $videos;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function addAdditionalContent(Link $additionalContent): PressRelease
    {
        $this->additionalContents->attach($additionalContent);

        return $this;
    }

    public function addContact(Contact $contact): PressRelease
    {
        $this->contacts->attach($contact);

        return $this;
    }

    public function addFile(FileReference $file): PressRelease
    {
        $this->files->attach($file);

        return $this;
    }

    public function addForcedpdfFile(FileReference $forcedpdfFile): PressRelease
    {
        $this->forcedpdfFile->attach($forcedpdfFile);

        return $this;
    }

    public function addImage(FileReference $image): PressRelease
    {
        $this->images->attach($image);

        return $this;
    }

    public function addPartnerSource(PartnerSource $partnerSource): PressRelease
    {
        $this->partnerSources->attach($partnerSource);

        return $this;
    }

    public function addPublidocFile(Link $publidocFile): PressRelease
    {
        $this->publidocFiles->attach($publidocFile);

        return $this;
    }

    public function addVideo(FileReference $video): PressRelease
    {
        $this->videos->attach($video);

        return $this;
    }

    public function getAdditionalContents(): ObjectStorage
    {
        return $this->additionalContents;
    }

    public function getAdditionalText(): string
    {
        return $this->additionalText;
    }

    public function getAnonymize(): bool
    {
        return $this->anonymize;
    }

    public function getAnonymizeTime(): ?DateTime
    {
        return $this->anonymizeTime;
    }

    public function getBodyText(): string
    {
        return $this->bodyText;
    }

    public function getContacts(): ObjectStorage
    {
        return $this->contacts;
    }

    public function getDateTime(): ?DateTime
    {
        return $this->dateTime;
    }

    public function getFiles(): ObjectStorage
    {
        return $this->files;
    }

    public function getForcedpdfFile(): ObjectStorage
    {
        return $this->forcedpdfFile;
    }

    public function getImages(): ObjectStorage
    {
        return $this->images;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function getPartnerSourceInfos(): string
    {
        return $this->partnerSourceInfos;
    }

    public function getPartnerSources(): ObjectStorage
    {
        return $this->partnerSources;
    }

    public function getPathSegment(): string
    {
        return $this->pathSegment;
    }

    public function getPublidocFiles(): ObjectStorage
    {
        return $this->publidocFiles;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): ?PressReleaseType
    {
        return $this->type;
    }

    public function getVideos(): ObjectStorage
    {
        return $this->videos;
    }

    public function hasAnonymize(): bool
    {
        return $this->anonymize;
    }

    public function initializeObject(): void
    {
        $this->additionalContents = $this->additionalContents ?? new ObjectStorage();
        $this->contacts = $this->contacts ?? new ObjectStorage();
        $this->files = $this->files ?? new ObjectStorage();
        $this->forcedpdfFile = $this->forcedpdfFile ?? new ObjectStorage();
        $this->images = $this->images ?? new ObjectStorage();
        $this->partnerSources = $this->partnerSources ?? new ObjectStorage();
        $this->publidocFiles = $this->publidocFiles ?? new ObjectStorage();
        $this->videos = $this->videos ?? new ObjectStorage();
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function removeAdditionalContent(Link $additionalContent): PressRelease
    {
        $this->additionalContents->detach($additionalContent);

        return $this;
    }

    public function removeContact(Contact $contact): PressRelease
    {
        $this->contacts->detach($contact);

        return $this;
    }

    public function removeFile(FileReference $file): PressRelease
    {
        $this->files->detach($file);

        return $this;
    }

    public function removeForcedpdfFile(FileReference $forcedpdfFile): PressRelease
    {
        $this->forcedpdfFile->detach($forcedpdfFile);

        return $this;
    }

    public function removeImage(FileReference $image): PressRelease
    {
        $this->images->detach($image);

        return $this;
    }

    public function removePartnerSource(PartnerSource $partnerSource): PressRelease
    {
        $this->partnerSources->detach($partnerSource);

        return $this;
    }

    public function removePublidocFile(Link $publidocFile): PressRelease
    {
        $this->publidocFiles->detach($publidocFile);

        return $this;
    }

    public function removeVideo(FileReference $video): PressRelease
    {
        $this->videos->detach($video);

        return $this;
    }

    public function setAdditionalContents(ObjectStorage $additionalContents): PressRelease
    {
        $this->additionalContents = $additionalContents;

        return $this;
    }

    public function setAdditionalText(string $additionalText): PressRelease
    {
        $this->additionalText = $additionalText;

        return $this;
    }

    public function setAnonymize(bool $anonymize): PressRelease
    {
        $this->anonymize = $anonymize;

        return $this;
    }

    public function setAnonymizeTime(DateTime $anonymizeTime): PressRelease
    {
        $this->anonymizeTime = $anonymizeTime;

        return $this;
    }

    public function setBodyText(string $bodyText): PressRelease
    {
        $this->bodyText = $bodyText;

        return $this;
    }

    public function setContacts(ObjectStorage $contacts): PressRelease
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function setDateTime(DateTime $dateTime): PressRelease
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function setFiles(ObjectStorage $files): PressRelease
    {
        $this->files = $files;

        return $this;
    }

    public function setForcedpdfFile(ObjectStorage $forcedpdfFile): PressRelease
    {
        $this->forcedpdfFile = $forcedpdfFile;

        return $this;
    }

    public function setHidden(bool $hidden): PressRelease
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function setImages(ObjectStorage $images): PressRelease
    {
        $this->images = $images;

        return $this;
    }

    public function setKeywords(string $keywords): PressRelease
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function setPartnerSourceInfos(string $partnerSourceInfos): PressRelease
    {
        $this->partnerSourceInfos = $partnerSourceInfos;

        return $this;
    }

    public function setPartnerSources(ObjectStorage $partnerSources): PressRelease
    {
        $this->partnerSources = $partnerSources;

        return $this;
    }

    public function setPathSegment(string $pathSegment): PressRelease
    {
        $this->pathSegment = $pathSegment;

        return $this;
    }

    public function setPublidocFiles(ObjectStorage $publidocFiles): PressRelease
    {
        $this->publidocFiles = $publidocFiles;

        return $this;
    }

    public function setSignature(string $signature): PressRelease
    {
        $this->signature = $signature;

        return $this;
    }

    public function setSource(string $source): PressRelease
    {
        $this->source = $source;

        return $this;
    }

    public function setSourceId(int $sourceId): PressRelease
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    public function setSubtitle(string $subtitle): PressRelease
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function setSummary(string $summary): PressRelease
    {
        $this->summary = $summary;

        return $this;
    }

    public function setTitle(string $title): PressRelease
    {
        $this->title = $title;

        return $this;
    }

    public function setType(PressReleaseType $type): PressRelease
    {
        $this->type = $type;

        return $this;
    }

    public function setVideos(ObjectStorage $videos): PressRelease
    {
        $this->videos = $videos;

        return $this;
    }
}
