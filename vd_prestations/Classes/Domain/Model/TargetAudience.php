<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Domain\Model;

class TargetAudience extends AbstractEntity
{
    protected string $name = '';
    protected array $updateFields = [
        'name'
    ];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): TargetAudience
    {
        $this->name = $name;

        return $this;
    }
}
