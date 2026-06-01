<?php

declare(strict_types=1);

namespace Vd\VdNews\Domain\Model;

use GeorgRinger\News\Domain\Model\FileReference as DefaultFileReference;

class FileReference extends DefaultFileReference
{
    protected string $owner = '';

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): FileReference
    {
        $this->owner = $owner;

        return $this;
    }
}
