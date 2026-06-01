<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Link extends AbstractEntity
{
    protected string $title = '';
    protected string $url = '';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setTitle(string $title): Link
    {
        $this->title = $title;

        return $this;
    }

    public function setUrl(string $url): Link
    {
        $this->url = $url;

        return $this;
    }
}
