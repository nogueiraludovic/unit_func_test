<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Department extends AbstractEntity
{
    protected string $code = '';
    protected string $label = '';

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setCode(string $code): Department
    {
        $this->code = $code;

        return $this;
    }

    public function setLabel(string $label): Department
    {
        $this->label = $label;

        return $this;
    }
}
