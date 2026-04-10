<?php

declare(strict_types=1);

namespace Vd\VdWebservice\Tests\Functional\Query;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use Vd\VdWebservice\Query\FileReferenceQuery;

final class FileReferenceQueryTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = [
        'core'
    ];

    #[Test]
    public function fetchOneReturnsFirstUidLocalOrderedBySortingForeign(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/FileReferenceQueryTest.csv');

        $query = new FileReferenceQuery($this->get(ConnectionPool::class));

        $this->assertSame(20, $query->fetchOne('image', 'tx_vdsmallads_domain_model_smallads', 100));
    }

    #[Test]
    public function fetchOneReturnsZeroWhenNoMatchFound(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/FileReferenceQueryTest.csv');

        $query = new FileReferenceQuery($this->get(ConnectionPool::class));

        $this->assertSame(0, $query->fetchOne('image', 'tx_vdsmallads_domain_model_smallads', 999));
    }

    #[Test]
    public function fetchOneExcludesHiddenRecords(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/FileReferenceQueryTest.csv');

        $query = new FileReferenceQuery($this->get(ConnectionPool::class));

        $this->assertSame(0, $query->fetchOne('image', 'tx_vdsmallads_domain_model_smallads', 200));
    }

    #[Test]
    public function fetchOneFiltersOnFieldname(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/FileReferenceQueryTest.csv');

        $query = new FileReferenceQuery($this->get(ConnectionPool::class));

        $this->assertSame(0, $query->fetchOne('thumbnail', 'tx_vdsmallads_domain_model_smallads', 200));
    }

    #[Test]
    public function fetchOneFiltersOnTablenames(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/FileReferenceQueryTest.csv');

        $query = new FileReferenceQuery($this->get(ConnectionPool::class));

        $this->assertSame(0, $query->fetchOne('image', 'other_table', 100));
    }
}
