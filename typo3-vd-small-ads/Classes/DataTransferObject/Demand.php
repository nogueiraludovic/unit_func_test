<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\DataTransferObject;

use Stringable;

use function serialize;
use function trim;

final class Demand implements Stringable
{
    public function __construct(private string $adType, private string $objectType, private string $query)
    {
        $this->adType = trim($adType);
        $this->objectType = trim($objectType);
        $this->query = trim($query);
    }

    public function __toString(): string
    {
        return serialize($this);
    }

    public function getAdType(): string
    {
        return $this->adType;
    }

    public function getObjectType(): string
    {
        return $this->objectType;
    }

    public function getQuery(): string
    {
        return $this->query;
    }
}
