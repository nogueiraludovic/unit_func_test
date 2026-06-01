<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class PartnerSource extends AbstractEntity
{
    protected string $description = '';
    protected ?FileReference $logo = null;
    protected string $title = '';

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLogo(): ?FileReference
    {
        return $this->logo;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description): PartnerSource
    {
        $this->description = $description;

        return $this;
    }

    public function setLogo(FileReference $logo): PartnerSource
    {
        $this->logo = $logo;

        return $this;
    }

    public function setTitle(string $title): PartnerSource
    {
        $this->title = $title;

        return $this;
    }
}
