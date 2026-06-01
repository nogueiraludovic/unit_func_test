<?php

declare(strict_types=1);

namespace Vd\VdAlertes\DomainObject;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

abstract class AbstractAlert extends AbstractEntity
{
    protected string $link = '';
    protected string $summary = '';
    protected string $title = '';

    public function getLink(): string
    {
        return $this->link;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
