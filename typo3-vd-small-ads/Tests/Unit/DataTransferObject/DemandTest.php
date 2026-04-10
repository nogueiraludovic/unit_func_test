<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\Tests\Unit\DataTransferObject;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vd\VdSmallAds\DataTransferObject\Demand;

use function serialize;

final class DemandTest extends TestCase
{
    #[Test]
    public function constructorTrimsWhitespaceFromAllFields(): void
    {
        $demand = new Demand('  sale  ', '  house  ', '  Geneva  ');

        $this->assertSame('sale', $demand->getAdType());
        $this->assertSame('house', $demand->getObjectType());
        $this->assertSame('Geneva', $demand->getQuery());
    }

    #[Test]
    public function getAdTypeReturnsConstructedValue(): void
    {
        $this->assertSame('offer', (new Demand('offer', '', ''))->getAdType());
    }

    #[Test]
    public function getObjectTypeReturnsConstructedValue(): void
    {
        $this->assertSame('apartment', (new Demand('', 'apartment', ''))->getObjectType());
    }

    #[Test]
    public function getQueryReturnsConstructedValue(): void
    {
        $this->assertSame('Lausanne', (new Demand('', '', 'Lausanne'))->getQuery());
    }

    #[Test]
    public function toStringReturnsSerializedRepresentation(): void
    {
        $demand = new Demand('sale', 'house', 'Geneva');

        $this->assertSame(serialize($demand), (string)$demand);
    }

    #[Test]
    public function toStringProducesUnserializableDemand(): void
    {
        $demand = new Demand('offer', 'studio', 'Lausanne');

        $restored = unserialize((string)$demand, ['allowed_classes' => [Demand::class]]);

        $this->assertInstanceOf(Demand::class, $restored);
        $this->assertSame('offer', $restored->getAdType());
        $this->assertSame('studio', $restored->getObjectType());
        $this->assertSame('Lausanne', $restored->getQuery());
    }
}
