<?php

declare(strict_types=1);

namespace Vd\VdMunicipalities\Tests\Unit\Domain\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdMunicipalities\Domain\Repository\MunicipalityRepository;

final class MunicipalityRepositoryTest extends UnitTestCase
{
    /** @var MockObject|QueryInterface */
    private $query;
    /** @var MockObject|QueryResultInterface */
    private $queryResult;
    /** @var MunicipalityRepository */
    private MunicipalityRepository $subject;

    public static function districtDataProvider(): array
    {
        return [
            'District ID 1' => [1],
            'District ID 42' => [42],
            'District ID 999' => [999]
        ];
    }

    /**
     * @test
     * @dataProvider districtDataProvider
     */
    public function checkFindByDistrictReturnsExpectedResult(int $district): void
    {
        $this->query->method('equals')->with('idDistrict', $district)->willReturn('constraint');

        self::assertSame($this->queryResult, $this->subject->findByDistrict($district));
    }

    /**
     * @dataProvider searchTermProvider
     * @test
     */
    public function checkSearchReturnsExpectedResult(string $term): void
    {
        $this->query->method('like')->willReturnCallback(static fn () => 'like');
        $this->query->method('logicalOr')->willReturn('logicalOrConstraint');

        self::assertSame($this->queryResult, $this->subject->search($term));
    }

    public static function searchTermProvider(): array
    {
        return [
            'Search "lausanne"' => ['lausanne'],
            'Search "1000"' => ['1000'],
            'Search "nyon"' => ['nyon']
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryResult = $this->createMock(QueryResultInterface::class);
        $this->query = $this->createMock(QueryInterface::class);
        $this->query->method('execute')->willReturn($this->queryResult);
        $this->query->method('matching')->willReturn($this->query);
        $this->subject = $this->getMockBuilder(MunicipalityRepository::class)
            ->onlyMethods(['createQuery'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->method('createQuery')->willReturn($this->query);
    }
}
