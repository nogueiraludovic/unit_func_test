<?php

declare(strict_types=1);

namespace Vd\VdContactService\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Department extends AbstractEntity
{
    protected string $code = '';
    protected string $name = '';

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
