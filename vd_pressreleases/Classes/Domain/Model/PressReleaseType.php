<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class PressReleaseType extends AbstractEntity
{
    protected string $title = '';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): PressReleaseType
    {
        $this->title = $title;

        return $this;
    }
}
