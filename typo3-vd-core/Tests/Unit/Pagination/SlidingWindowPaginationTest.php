<?php

declare(strict_types=1);

namespace Vd\VdCore\Pagination;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Pagination\PaginatorInterface;

final class SlidingWindowPaginationTest extends TestCase
{
    protected MockObject $paginator;

    #[Test]
    public function getAllPageNumbersReturnsCorrectRange(): void
    {
        $this->paginator->method('getNumberOfPages')->willReturn(10);
        $this->paginator->method('getCurrentPageNumber')->willReturn(5);

        $this->assertSame([3, 4, 5, 6, 7], (new SlidingWindowPagination($this->paginator))->getAllPageNumbers());
    }

    #[Test]
    public function getPreviousPageNumberReturnsNullWhenCurrentPageIsBeyondLastPage(): void
    {
        $this->paginator->method('getNumberOfPages')->willReturn(5);
        $this->paginator->method('getCurrentPageNumber')->willReturn(10);

        $this->assertNull((new SlidingWindowPagination($this->paginator))->getPreviousPageNumber());
    }

    #[Test]
    public function getStartAndEndRecordNumbersReturnZeroWhenOutOfBounds(): void
    {
        $this->paginator->method('getNumberOfPages')->willReturn(5);
        $this->paginator->method('getCurrentPageNumber')->willReturn(10);

        $pagination = new SlidingWindowPagination($this->paginator);

        $this->assertSame(0, $pagination->getStartRecordNumber());
        $this->assertSame(0, $pagination->getEndRecordNumber());
    }

    #[Test]
    public function getStartAndEndRecordNumbersReturnCorrectValues(): void
    {
        $this->paginator->method('getNumberOfPages')->willReturn(10);
        $this->paginator->method('getCurrentPageNumber')->willReturn(3);
        $this->paginator->method('getKeyOfFirstPaginatedItem')->willReturn(20);
        $this->paginator->method('getKeyOfLastPaginatedItem')->willReturn(29);

        $pagination = new SlidingWindowPagination($this->paginator);

        $this->assertSame(21, $pagination->getStartRecordNumber());
        $this->assertSame(30, $pagination->getEndRecordNumber());
    }

    #[Test]
    public function getNextPageNumberReturnsNullWhenAtEnd(): void
    {
        $this->paginator->method('getNumberOfPages')->willReturn(5);
        $this->paginator->method('getCurrentPageNumber')->willReturn(5);

        $this->assertNull((new SlidingWindowPagination($this->paginator))->getNextPageNumber());
    }

    #[Test]
    public function getNextPageNumberReturnsIncrementedValue(): void
    {
        $this->paginator->method('getNumberOfPages')->willReturn(5);
        $this->paginator->method('getCurrentPageNumber')->willReturn(3);

        $this->assertSame(4, (new SlidingWindowPagination($this->paginator))->getNextPageNumber());
    }

    #[Test]
    public function getPreviousPageNumberReturnsNullWhenBelowFirst(): void
    {
        $this->paginator->method('getNumberOfPages')->willReturn(5);
        $this->paginator->method('getCurrentPageNumber')->willReturn(1);

        $this->assertNull((new SlidingWindowPagination($this->paginator))->getPreviousPageNumber());
    }

    #[Test]
    public function getPreviousPageNumberReturnsDecrementedValue(): void
    {
        $this->paginator->method('getNumberOfPages')->willReturn(5);
        $this->paginator->method('getCurrentPageNumber')->willReturn(4);

        $this->assertSame(3, (new SlidingWindowPagination($this->paginator))->getPreviousPageNumber());
    }

    #[Test]
    public function displayRangeShrinksWhenFewPages(): void
    {
        $this->paginator->method('getNumberOfPages')->willReturn(3);
        $this->paginator->method('getCurrentPageNumber')->willReturn(2);

        $this->assertSame([1, 2, 3], (new SlidingWindowPagination($this->paginator, 10))->getAllPageNumbers());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->paginator = $this->createMock(PaginatorInterface::class);
    }
}
