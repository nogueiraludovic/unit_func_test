<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\Tests\Unit\DataTransferObject;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vd\VdSmallAds\DataTransferObject\Demand;

final class DemandTest extends TestCase
{
    #[Test]
    public function constructorTrimsValuesAndGettersReturnThem(): void
    {
        $demand = new Demand(' sale ', ' car ', ' electric ');

        $this->assertSame('sale', $demand->getAdType());
        $this->assertSame('car', $demand->getObjectType());
        $this->assertSame('electric', $demand->getQuery());
    }

    #[Test]
    public function toStringSerializesCurrentDemand(): void
    {
        $demand = new Demand('sale', 'car', 'electric');

        $this->assertSame($demand, unserialize((string)$demand, ['allowed_classes' => [Demand::class]]));
    }
}
