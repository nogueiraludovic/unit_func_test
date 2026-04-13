<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\Tests\Unit\Domain\Repository;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use Vd\VdSmallAds\DataTransferObject\Demand;
use Vd\VdSmallAds\Domain\Repository\SmallAdsRepository;

final class SmallAdsRepositoryTest extends TestCase
{
    #[Test]
    public function findAllConfiguresQuerySettingsAndExecutesQuery(): void
    {
        $querySettings = $this->createMock(QuerySettingsInterface::class);
        $query = $this->createMock(QueryInterface::class);
        $result = $this->createMock(QueryResultInterface::class);

        $query->expects(self::once())->method('getQuerySettings')->willReturn($querySettings);
        $querySettings->expects(self::once())->method('setIgnoreEnableFields')->with(true)->willReturnSelf();
        $querySettings->expects(self::once())->method('setIncludeDeleted')->with(true)->willReturnSelf();
        $querySettings->expects(self::once())->method('setRespectStoragePage')->with(false)->willReturnSelf();
        $query->expects(self::once())->method('execute')->willReturn($result);

        $repository = $this->createRepositoryMock(['createQuery']);
        $repository->expects(self::once())->method('createQuery')->willReturn($query);

        $this->assertSame($result, $repository->findAll(true, true, false));
    }

    #[Test]
    public function findDemandedReturnsAllWhenDemandIsNull(): void
    {
        $querySettings = $this->createMock(QuerySettingsInterface::class);
        $query = $this->createMock(QueryInterface::class);
        $result = $this->createMock(QueryResultInterface::class);

        $query->expects(self::once())->method('getQuerySettings')->willReturn($querySettings);
        $querySettings->expects(self::once())->method('setRespectStoragePage')->with(false)->willReturnSelf();
        $query->expects(self::never())->method('matching');
        $query->expects(self::once())->method('execute')->willReturn($result);

        $repository = $this->createRepositoryMock(['createQuery']);
        $repository->expects(self::once())->method('createQuery')->willReturn($query);

        $this->assertSame($result, $repository->findDemanded());
    }

    #[Test]
    public function findDemandedBuildsConstraintsWhenDemandContainsFilters(): void
    {
        $querySettings = $this->createMock(QuerySettingsInterface::class);
        $query = $this->createMock(QueryInterface::class);
        $result = $this->createMock(QueryResultInterface::class);

        $constraintCategory = new \stdClass();
        $constraintObjectType = new \stdClass();
        $constraintContentLike = new \stdClass();
        $constraintTitleLike = new \stdClass();
        $constraintSearchOr = new \stdClass();
        $constraintAll = new \stdClass();

        $query->expects(self::once())->method('getQuerySettings')->willReturn($querySettings);
        $querySettings->expects(self::once())->method('setRespectStoragePage')->with(false)->willReturnSelf();
        $query->expects(self::exactly(2))
            ->method('equals')
            ->willReturnOnConsecutiveCalls($constraintCategory, $constraintObjectType);
        $query->expects(self::exactly(2))
            ->method('like')
            ->willReturnOnConsecutiveCalls($constraintContentLike, $constraintTitleLike);
        $query->expects(self::once())
            ->method('logicalOr')
            ->with($constraintContentLike, $constraintTitleLike)
            ->willReturn($constraintSearchOr);
        $query->expects(self::once())
            ->method('logicalAnd')
            ->with($constraintCategory, $constraintObjectType, $constraintSearchOr)
            ->willReturn($constraintAll);
        $query->expects(self::once())->method('matching')->with($constraintAll);
        $query->expects(self::once())->method('execute')->willReturn($result);

        $repository = $this->createRepositoryMock(['createQuery']);
        $repository->expects(self::once())->method('createQuery')->willReturn($query);

        $demand = new Demand('cars', 'electric', 'model');

        $this->assertSame($result, $repository->findDemanded($demand));
    }

    #[Test]
    public function removeAllRemovesEachFoundObject(): void
    {
        $first = new \stdClass();
        $second = new \stdClass();

        $repository = $this->createRepositoryMock(['findAll', 'remove']);
        $repository->expects(self::once())->method('findAll')->with(true, true, false)->willReturn([$first, $second]);
        $repository->expects(self::exactly(2))
            ->method('remove')
            ->with(self::callback(static fn (object $item): bool => $item === $first || $item === $second));

        $repository->removeAll();
    }

    /**
     * @param list<string> $methods
     */
    private function createRepositoryMock(array $methods): SmallAdsRepository&MockObject
    {
        return $this->getMockBuilder(SmallAdsRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods($methods)
            ->getMock();
    }
}
