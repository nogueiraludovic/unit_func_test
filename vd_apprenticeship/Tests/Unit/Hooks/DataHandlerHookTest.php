<?php

declare(strict_types=1);

namespace Vd\VdApprenticeship\Tests\Unit\Hooks;

use Doctrine\DBAL\ForwardCompatibility\Result;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\QueryRestrictionContainerInterface;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdApprenticeship\Hooks\DataHandlerHook;

final class DataHandlerHookTest extends UnitTestCase
{
    /** @var Connection|MockObject */
    private $connection;
    /** @var ConnectionPool|MockObject */
    private $connectionPool;
    /** @var DataHandler|MockObject */
    private $dataHandler;
    /** @var DataHandlerHook */
    private DataHandlerHook $subject;

    /**
     * @test
     */
    public function checkProcessDatamapExitsEarlyIfTitleEmpty(): void
    {
        $this->connection->expects(self::never())->method('update');
        $this->mockDatabase(false);
        $this->subject->processDatamap_afterDatabaseOperations(
            'update',
            'tx_vdapprenticeship_apprenticeship',
            '123',
            [],
            $this->dataHandler
        );
    }

    /**
     * @test
     */
    public function checkProcessDatamapHandlesNewStatusAndMapsId(): void
    {
        $this->connection
            ->expects(self::once())
            ->method('update')
            ->with(
                'tx_vdapprenticeship_apprenticeship',
                ['title' => 'ACME, John Doe'],
                ['uid' => 456]
            );
        $this->dataHandler->substNEWwithIDs = ['NEW123' => 456];
        $this->mockDatabase(['company' => 'ACME', 'first_name' => 'John', 'last_name' => 'Doe']);
        $this->subject->processDatamap_afterDatabaseOperations(
            'new',
            'tx_vdapprenticeship_apprenticeship',
            'NEW123',
            [],
            $this->dataHandler
        );
    }

    /**
     * @test
     */
    public function checkProcessDatamapIgnoresOtherTables(): void
    {
        $this->connection->expects(self::never())->method('update');
        $this->subject->processDatamap_afterDatabaseOperations(
            'update',
            'irrelevant_table',
            '123',
            [],
            $this->dataHandler
        );
    }

    /**
     * @test
     */
    public function checkProcessDatamapUpdatesRecordOnUpdate(): void
    {
        $this->connection
            ->expects(self::once())
            ->method('update')
            ->with(
                'tx_vdapprenticeship_apprenticeship',
                ['title' => 'ACME, John Doe'],
                ['uid' => 123]
            );
        $this->mockDatabase(['company' => 'ACME', 'first_name' => 'John', 'last_name' => 'Doe']);
        $this->subject->processDatamap_afterDatabaseOperations(
            'update',
            'tx_vdapprenticeship_apprenticeship',
            '123',
            [],
            $this->dataHandler
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->connection = $this->createMock(Connection::class);
        $this->connectionPool = $this->createMock(ConnectionPool::class);
        $this->connectionPool->method('getConnectionForTable')->willReturn($this->connection);
        $this->dataHandler = $this->createMock(DataHandler::class);
        $this->subject = new DataHandlerHook($this->connectionPool);
    }

    private function mockDatabase($data): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAssociative')->willReturn($data);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('createNamedParameter')->willReturn('123');
        $queryBuilder->method('execute')->willReturn($result);
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder
            ->method('getRestrictions')
            ->willReturn($this->createMock(QueryRestrictionContainerInterface::class));
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();

        $this->connectionPool->method('getQueryBuilderForTable')->with('tt_address')->willReturn($queryBuilder);
    }
}
