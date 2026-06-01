<?php

declare(strict_types=1);

namespace Vd\VdSolr\Tests\Unit\Hooks;

use ApacheSolrForTypo3\Solr\Domain\Search\Query\Query;
use Psr\Http\Message\ServerRequestInterface;
use Solarium\QueryType\Select\Query\FilterQuery;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdSolr\Hooks\ModifySearchQueryHook;

class ModifySearchQueryHookTest extends UnitTestCase
{
    protected ModifySearchQueryHook $subject;

    public function testPidFilterIsRemovedIfCategoryFilterIsPresent(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn(['tx_solr' => ['filter' => ['category:books']]]);
        $request->method('getQueryParams')->willReturn([]);

        $GLOBALS['TYPO3_REQUEST'] = $request;

        $query = new Query();
        $query->setFilterQueries([
            new FilterQuery(['key' => 'fq1', 'query' => 'pid:123']),
            new FilterQuery(['key' => 'fq2', 'query' => 'category:books'])
        ]);

        $filters = $this->subject->modifyQuery($query)->getFilterQueries();

        self::assertArrayHasKey('fq2', $filters);
        self::assertArrayNotHasKey('fq1', $filters);
        self::assertCount(1, $filters);
        self::assertSame('category:books', $filters['fq2']->getOption('query'));
    }

    public function testQueryIsUnchangedIfFilterIsMissing(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([]);
        $request->method('getQueryParams')->willReturn([]);

        $GLOBALS['TYPO3_REQUEST'] = $request;

        $query = new Query();
        $query->setFilterQueries([
            new FilterQuery(['key' => 'fq1', 'query' => 'pid:123']),
            new FilterQuery(['key' => 'fq2', 'query' => 'category:books'])
        ]);

        self::assertCount(2, $this->subject->modifyQuery($query)->getFilterQueries());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new ModifySearchQueryHook();
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['TYPO3_REQUEST']);

        parent::tearDown();
    }
}
