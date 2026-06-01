<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
declare(strict_types=1);

namespace Vd\VdNews\Domain\Model;

use GeorgRinger\News\Domain\Model\NewsDefault;

class News extends NewsDefault
{
    protected int $eventEndDate = 0;
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\News>
     */
    protected ?\TYPO3\CMS\Extbase\Persistence\ObjectStorage $selectedArticle = null;
    protected bool $vdDisplayDateInTitle = false;

    public function __construct()
    {
        parent::__construct();

        $this->selectedArticle = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    public function getEventEndDate(): int
    {
        return $this->eventEndDate;
    }

    public function getRelatedImages(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->getRelatedByType(\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE);
    }

    public function getRelatedVideos(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->getRelatedByType(\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_VIDEO);
    }

    public function getSelectedArticle(): ?\GeorgRinger\News\Domain\Model\News
    {
        $this->selectedArticle->rewind();

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->selectedArticle->current();
    }

    public function getVdDisplayDateInTitle(): bool
    {
        return $this->vdDisplayDateInTitle;
    }

    public function setEventEndDate(int $eventEndDate): void
    {
        $this->eventEndDate = $eventEndDate;
    }

    public function setVdDisplayDateInTitle(bool $vdDisplayDateInTitle): void
    {
        $this->vdDisplayDateInTitle = $vdDisplayDateInTitle;
    }

    protected function getRelatedByType(int $type): \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        $isFirst = true;
        $objectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

        foreach ($this->falMedia as $media) {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            if ($isFirst === true || $media->getOriginalResource()->getType() !== $type) {
                $isFirst = false;

                continue;
            }

            $objectStorage->attach($media);
        }

        return $objectStorage;
    }
}
