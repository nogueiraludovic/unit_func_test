<?php

declare(strict_types=1);

namespace Vd\VdMunicipalities\Tests\Domain\Repository;

use Doctrine\DBAL\Result;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdMunicipalities\Domain\Repository\PageRepository;

final class PageRepositoryTest extends UnitTestCase
{
    /** @var MockObject|QueryBuilder */
    private $queryBuilder;
    /** @var PageRepository */
    private PageRepository $subject;

    /**
     * @dataProvider recordProvider
     * @test
     */
    public function checkFindByInstitutionReturnsExpectedResults(int $institutionId, array $expected): void
    {
        $this->mockDatabase($expected);

        self::assertSame($expected, $this->subject->findByInstitution($institutionId));
    }

    public static function recordProvider(): array
    {
        return [
            'No record' => [
                999,
                []
            ],
            'With records' => [
                123,
                [
                    ['tx_vdmunicipalitiessearch_institution' => 123, 'uid' => 1000001],
                    ['tx_vdmunicipalitiessearch_institution' => 123, 'uid' => 1000002]
                ]
            ]
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryBuilder = $this->createMock(QueryBuilder::class);

        $connection = $this->createMock(ConnectionPool::class);
        $connection->method('getQueryBuilderForTable')->with('pages')->willReturn($this->queryBuilder);

        $this->subject = new PageRepository($connection);
    }

    private function mockDatabase(array $data): void
    {
        $expressionBuilder = $this->createMock(ExpressionBuilder::class);
        $expressionBuilder->method('eq')->willReturn('eq1');
        $expressionBuilder->method('gt')->willReturn('gt1');

        $result = $this->createMock(Result::class);
        $result->method('fetchAllAssociative')->willReturn($data);

        $this->queryBuilder->method('execute')->willReturn($result);
        $this->queryBuilder->method('expr')->willReturn($expressionBuilder);
        $this->queryBuilder->method('from')->willReturnSelf();
        $this->queryBuilder->method('select')->willReturnSelf();
        $this->queryBuilder->method('where')->with('eq1', 'gt1')->willReturnSelf();
    }
}
