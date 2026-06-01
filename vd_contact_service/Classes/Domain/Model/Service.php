<?php

declare(strict_types=1);

namespace Vd\VdContactService\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Service extends AbstractEntity
{
    protected string $code = '';
    protected ?Department $department = null;
    protected string $name = '';
    protected string $referencePage = '';

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getReferencePage(): string
    {
        return $this->referencePage;
    }
}
