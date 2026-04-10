<?php

declare(strict_types=1);

namespace Vd\VdWebservice\Tests\Unit\Query;

use Doctrine\DBAL\Result;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use Vd\VdWebservice\Imaging\ImageConverter;
use Vd\VdWebservice\Query\SmallAdsQuery;

final class SmallAdsQueryTest extends TestCase
{
    private MockObject $imageConverter;

    #[Test]
    public function fetchAllReturnsEmptyArrayWhenNoRowsFound(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAssociative')->willReturn(false);

        $query = new SmallAdsQuery($this->buildConnectionPool($result), $this->imageConverter);

        $this->assertSame([], $query->fetchAll());
    }

    #[Test]
    public function fetchAllReturnsRowWithoutCallingImageConverterWhenImageIsZero(): void
    {
        $row = $this->buildRow(['uid' => 1, 'image' => 0, 'title' => 'Bike']);

        $result = $this->createMock(Result::class);
        $result->method('fetchAssociative')->willReturnOnConsecutiveCalls($row, false);

        $this->imageConverter->expects($this->never())->method('base64Image');

        $records = (new SmallAdsQuery($this->buildConnectionPool($result), $this->imageConverter))->fetchAll();

        $this->assertCount(1, $records);
        $this->assertSame('Bike', $records[0]['title']);
    }

    #[Test]
    public function fetchAllCallsImageConverterForRowsWithNonZeroImage(): void
    {
        $row = $this->buildRow(['uid' => 2, 'image' => 1, 'title' => 'Moto']);

        $result = $this->createMock(Result::class);
        $result->method('fetchAssociative')->willReturnOnConsecutiveCalls($row, false);

        $this->imageConverter->expects($this->once())
            ->method('base64Image')
            ->with('image', 'tx_vdsmallads_domain_model_smallads', 2)
            ->willReturn('data:image/jpg;base64,abc123');

        $records = (new SmallAdsQuery($this->buildConnectionPool($result), $this->imageConverter))->fetchAll();

        $this->assertCount(1, $records);
        $this->assertSame('data:image/jpg;base64,abc123', $records[0]['image']);
    }

    #[Test]
    public function fetchAllKeepsOriginalImageValueWhenConverterReturnsEmptyString(): void
    {
        $row = $this->buildRow(['uid' => 3, 'image' => 1, 'title' => 'Car']);

        $result = $this->createMock(Result::class);
        $result->method('fetchAssociative')->willReturnOnConsecutiveCalls($row, false);

        $this->imageConverter->method('base64Image')->willReturn('');

        $records = (new SmallAdsQuery($this->buildConnectionPool($result), $this->imageConverter))->fetchAll();

        $this->assertCount(1, $records);
        $this->assertSame(1, $records[0]['image']);
    }

    #[Test]
    public function fetchAllReturnsAllRowsIncludingThoseWithAndWithoutImages(): void
    {
        $rowWithImage = $this->buildRow(['uid' => 1, 'image' => 1, 'title' => 'With image']);
        $rowWithoutImage = $this->buildRow(['uid' => 2, 'image' => 0, 'title' => 'No image']);

        $result = $this->createMock(Result::class);
        $result->method('fetchAssociative')->willReturnOnConsecutiveCalls(
            $rowWithImage,
            $rowWithoutImage,
            false
        );

        $this->imageConverter->method('base64Image')->willReturn('data:image/jpg;base64,xyz');

        $records = (new SmallAdsQuery($this->buildConnectionPool($result), $this->imageConverter))->fetchAll();

        $this->assertCount(2, $records);
        $this->assertSame('data:image/jpg;base64,xyz', $records[0]['image']);
        $this->assertSame(0, $records[1]['image']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->imageConverter = $this->createMock(ImageConverter::class);
    }

    private function buildRow(array $overrides = []): array
    {
        return array_merge([
            'cat' => '',
            'cat2' => '',
            'content' => '',
            'crdateexternal' => 0,
            'email' => '',
            'image' => 0,
            'iscommercial' => 0,
            'phone' => '',
            'reviewed' => 0,
            'slug' => '',
            'title' => '',
            'uid' => 1,
            'user' => ''
        ], $overrides);
    }

    private function buildConnectionPool(Result $result): ConnectionPool
    {
        $qb = $this->createMock(QueryBuilder::class);
        $qb->method('select')->willReturnSelf();
        $qb->method('from')->willReturnSelf();
        $qb->method('executeQuery')->willReturn($result);

        $connectionPool = $this->createMock(ConnectionPool::class);
        $connectionPool->method('getQueryBuilderForTable')->willReturn($qb);

        return $connectionPool;
    }
}
