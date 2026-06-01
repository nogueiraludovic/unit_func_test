<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Domain\Model;

class Url extends AbstractEntity
{
    protected string $fieldname = '';
    protected string $hash = '';
    protected string $title = '';
    protected string $url = '';

    public function getFieldname(): string
    {
        return $this->fieldname;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setFieldname(string $fieldname): Url
    {
        $this->fieldname = $fieldname;

        return $this;
    }

    public function setHash(string $hash): Url
    {
        $this->hash = $hash;

        return $this;
    }

    public function setTitle(string $title): Url
    {
        $this->title = $title;

        return $this;
    }

    public function setUrl(string $url): Url
    {
        $this->url = $url;

        return $this;
    }
}
