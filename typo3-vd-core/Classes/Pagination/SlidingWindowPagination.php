<?php

declare(strict_types=1);

namespace Vd\VdCore\Pagination;

/** @noinspection PhpDeprecationInspection */
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use TYPO3\CMS\Core\Pagination\PaginationInterface;
use TYPO3\CMS\Core\Pagination\PaginatorInterface;

use function floor;
use function max;
use function min;
use function range;

final class SlidingWindowPagination implements PaginationInterface
{
    private int $displayRangeEnd = 0;
    private int $displayRangeStart = 0;
    private bool $hasLessPages = false;
    private bool $hasMorePages = false;
    private int $maximumNumberOfLinks = 5;

    public function __construct(private readonly PaginatorInterface $paginator, int $maximumNumberOfLinks = 5)
    {
        if ($maximumNumberOfLinks > 0) {
            $this->maximumNumberOfLinks = $maximumNumberOfLinks;
        }

        $this->calculateDisplayRange();
    }

    public function getAllPageNumbers(): array
    {
        return range($this->displayRangeStart, $this->displayRangeEnd);
    }

    #[CodeCoverageIgnore]
    public function getDisplayRangeEnd(): int
    {
        return $this->displayRangeEnd;
    }

    #[CodeCoverageIgnore]
    public function getDisplayRangeStart(): int
    {
        return $this->displayRangeStart;
    }

    public function getEndRecordNumber(): int
    {
        if ($this->paginator->getCurrentPageNumber() > $this->paginator->getNumberOfPages()) {
            return 0;
        }

        return $this->paginator->getKeyOfLastPaginatedItem() + 1;
    }

    #[CodeCoverageIgnore]
    public function getFirstPageNumber(): int
    {
        return 1;
    }

    #[CodeCoverageIgnore]
    public function getHasLessPages(): bool
    {
        return $this->hasLessPages;
    }

    #[CodeCoverageIgnore]
    public function getHasMorePages(): bool
    {
        return $this->hasMorePages;
    }

    #[CodeCoverageIgnore]
    public function getLastPageNumber(): int
    {
        return $this->paginator->getNumberOfPages();
    }

    public function getNextPageNumber(): ?int
    {
        $nextPage = $this->paginator->getCurrentPageNumber() + 1;

        return $nextPage <= $this->paginator->getNumberOfPages() ? $nextPage : null;
    }

    #[CodeCoverageIgnore]
    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }

    public function getPreviousPageNumber(): ?int
    {
        $previousPage = $this->paginator->getCurrentPageNumber() - 1;

        if ($previousPage > $this->paginator->getNumberOfPages()) {
            return null;
        }

        return $previousPage >= $this->getFirstPageNumber() ? $previousPage : null;
    }

    public function getStartRecordNumber(): int
    {
        if ($this->paginator->getCurrentPageNumber() > $this->paginator->getNumberOfPages()) {
            return 0;
        }

        return $this->paginator->getKeyOfFirstPaginatedItem() + 1;
    }

    private function calculateDisplayRange(): void
    {
        $maximumNumberOfLinks = $this->maximumNumberOfLinks;
        $numberOfPages = $this->paginator->getNumberOfPages();

        if ($maximumNumberOfLinks > $numberOfPages) {
            $maximumNumberOfLinks = $numberOfPages;
        }

        $currentPage = $this->paginator->getCurrentPageNumber();
        $delta = floor($maximumNumberOfLinks / 2);

        $this->displayRangeStart = (int)($currentPage - $delta);
        $this->displayRangeEnd = (int)($currentPage + $delta - ($maximumNumberOfLinks % 2 === 0 ? 1 : 0));

        if ($this->displayRangeStart < 1) {
            $this->displayRangeEnd -= $this->displayRangeStart - 1;
        }

        if ($this->displayRangeEnd > $numberOfPages) {
            $this->displayRangeStart -= $this->displayRangeEnd - $numberOfPages;
        }

        $this->displayRangeEnd = (int)min($this->displayRangeEnd, $numberOfPages);
        $this->displayRangeStart = (int)max($this->displayRangeStart, 1);
        $this->hasLessPages = $this->displayRangeStart > 2;
        $this->hasMorePages = $this->displayRangeEnd + 1 < $this->paginator->getNumberOfPages();
    }
}
