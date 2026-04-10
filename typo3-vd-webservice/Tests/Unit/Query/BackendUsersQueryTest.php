<?php

declare(strict_types=1);

namespace Vd\VdWebservice\Tests\Unit\Query;

use Doctrine\DBAL\Result;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\QueryRestrictionContainerInterface;
use Vd\VdWebservice\Query\BackendUsersQuery;

final class BackendUsersQueryTest extends TestCase
{
    #[Test]
    public function fetchAllContributorsReturnsEmptyArrayWhenNoUsersFound(): void
    {
        $userResult = $this->createMock(Result::class);
        $userResult->method('fetchAssociative')->willReturn(false);

        $connectionPool = $this->buildConnectionPool(
            userResult: $userResult,
            groupResult: $this->createMock(Result::class)
        );

        $query = new BackendUsersQuery($connectionPool);
        $this->assertSame([], $query->fetchAllContributors());
    }

    #[Test]
    public function fetchAllContributorsReturnsFormattedRecordsWithGroupTitles(): void
    {
        $userResult = $this->createMock(Result::class);
        $userResult->method('fetchAssociative')->willReturnOnConsecutiveCalls(
            [
                'uid' => 1,
                'email' => 'alice@example.com',
                'realName' => 'Alice Smith',
                'username' => 'alice',
                'usergroup' => '10,20'
            ],
            false
        );

        $groupResult = $this->createMock(Result::class);
        $groupResult->method('fetchAssociative')->willReturnOnConsecutiveCalls(
            ['uid' => 10, 'title' => 'Editors'],
            ['uid' => 20, 'title' => 'Authors'],
            false
        );

        $connectionPool = $this->buildConnectionPool(
            userResult: $userResult,
            groupResult: $groupResult
        );

        $records = (new BackendUsersQuery($connectionPool))->fetchAllContributors();

        $this->assertCount(1, $records);
        $this->assertSame(1, $records[0]['uid']);
        $this->assertSame('alice@example.com', $records[0]['email']);
        $this->assertSame('Alice Smith', $records[0]['realName']);
        $this->assertSame('alice', $records[0]['username']);
        $this->assertSame('Editors, Authors', $records[0]['usergroup']);
    }

    #[Test]
    public function fetchAllContributorsSkipsGroupsNotFoundInLookup(): void
    {
        $userResult = $this->createMock(Result::class);
        $userResult->method('fetchAssociative')->willReturnOnConsecutiveCalls(
            ['uid' => 2, 'email' => 'bob@example.com', 'realName' => 'Bob', 'username' => 'bob', 'usergroup' => '99'],
            false
        );

        $groupResult = $this->createMock(Result::class);
        $groupResult->method('fetchAssociative')->willReturn(false);

        $connectionPool = $this->buildConnectionPool(
            userResult: $userResult,
            groupResult: $groupResult
        );

        $records = (new BackendUsersQuery($connectionPool))->fetchAllContributors();

        $this->assertCount(1, $records);
        $this->assertSame('', $records[0]['usergroup']);
    }

    #[Test]
    public function fetchAllContributorsDeduplicatesGroupUids(): void
    {
        $userResult = $this->createMock(Result::class);
        $userResult->method('fetchAssociative')->willReturnOnConsecutiveCalls(
            ['uid' => 1, 'email' => 'a@b.com', 'realName' => 'A', 'username' => 'a', 'usergroup' => '5,5'],
            false
        );

        $groupResult = $this->createMock(Result::class);
        $groupResult->method('fetchAssociative')->willReturnOnConsecutiveCalls(
            ['uid' => 5, 'title' => 'Writers'],
            false
        );

        $groupQb = $this->buildQueryBuilder($groupResult);
        $groupQb->method('where')->willReturnSelf();

        $connectionPool = $this->createMock(ConnectionPool::class);
        $connectionPool->method('getQueryBuilderForTable')
            ->willReturnCallback(static function (string $table) use ($groupQb): QueryBuilder {
                $userQb = (new \PHPUnit\Framework\MockObject\MockObjectGenerator())->getMock(QueryBuilder::class, [], [], '', false);
                if ($table === 'be_groups') {
                    return $groupQb;
                }

                return $userQb;
            });

        $userQb = $this->buildQueryBuilder($userResult);
        $connectionPool = $this->buildConnectionPool(
            userResult: $userResult,
            groupResult: $groupResult
        );

        $records = (new BackendUsersQuery($connectionPool))->fetchAllContributors();

        $this->assertCount(1, $records);
        $this->assertSame('Writers', $records[0]['usergroup']);
    }

    #[Test]
    public function fetchAllContributorsIncludesEnvironmentFromEnv(): void
    {
        $userResult = $this->createMock(Result::class);
        $userResult->method('fetchAssociative')->willReturnOnConsecutiveCalls(
            ['uid' => 3, 'email' => 'c@d.com', 'realName' => 'C', 'username' => 'c', 'usergroup' => ''],
            false
        );

        $groupResult = $this->createMock(Result::class);
        $groupResult->method('fetchAssociative')->willReturn(false);

        $connectionPool = $this->buildConnectionPool(
            userResult: $userResult,
            groupResult: $groupResult
        );

        putenv('DGNSI_APP_NAME=test-env');

        $records = (new BackendUsersQuery($connectionPool))->fetchAllContributors();

        putenv('DGNSI_APP_NAME');

        $this->assertCount(1, $records);
        $this->assertSame('test-env', $records[0]['environment']);
    }

    private function buildQueryBuilder(Result $result): MockObject
    {
        $expr = $this->createMock(ExpressionBuilder::class);
        $expr->method('eq')->willReturn('1=1');
        $expr->method('notLike')->willReturn('1=1');
        $expr->method('comparison')->willReturn('1=1');
        $expr->method('in')->willReturn('1=1');

        $restrictions = $this->createMock(QueryRestrictionContainerInterface::class);
        $restrictions->method('removeAll')->willReturnSelf();
        $restrictions->method('add')->willReturnSelf();

        $qb = $this->createMock(QueryBuilder::class);
        $qb->method('getRestrictions')->willReturn($restrictions);
        $qb->method('select')->willReturnSelf();
        $qb->method('from')->willReturnSelf();
        $qb->method('where')->willReturnSelf();
        $qb->method('expr')->willReturn($expr);
        $qb->method('createNamedParameter')->willReturnArgument(0);
        $qb->method('escapeLikeWildcards')->willReturnArgument(0);
        $qb->method('quoteIdentifier')->willReturnArgument(0);
        $qb->method('executeQuery')->willReturn($result);

        return $qb;
    }

    private function buildConnectionPool(Result $userResult, Result $groupResult): ConnectionPool
    {
        $userQb = $this->buildQueryBuilder($userResult);
        $groupQb = $this->buildQueryBuilder($groupResult);

        $connectionPool = $this->createMock(ConnectionPool::class);
        $connectionPool->method('getQueryBuilderForTable')
            ->willReturnCallback(static function (string $table) use ($userQb, $groupQb): QueryBuilder {
                return match ($table) {
                    'be_users' => $userQb,
                    'be_groups' => $groupQb,
                    default => throw new \InvalidArgumentException('Unexpected table: ' . $table),
                };
            });

        return $connectionPool;
    }
}
