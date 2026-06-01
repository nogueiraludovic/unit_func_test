<?php

declare(strict_types=1);

namespace Vd\VdPowermail\Domain\Model;

use In2code\Powermail\Domain\Model\Field as DefaultField;

class Field extends DefaultField
{
    protected string $errorText = '';
    protected string $helpText = '';

    public function getErrorText(): string
    {
        return $this->errorText;
    }

    public function getHelpText(): string
    {
        return $this->helpText;
    }
}
