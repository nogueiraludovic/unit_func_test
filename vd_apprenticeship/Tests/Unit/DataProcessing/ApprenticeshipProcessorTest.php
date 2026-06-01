<?php

declare(strict_types=1);

namespace Vd\VdApprenticeship\Tests\Unit\DataProcessing;

use Doctrine\DBAL\ForwardCompatibility\Result;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdApprenticeship\DataProcessing\ApprenticeshipProcessor;

final class ApprenticeshipProcessorTest extends UnitTestCase
{
    protected $resetSingletonInstances = true;

    /** @var ConnectionPool|MockObject */
    private $connection;
    /** @var ContentObjectRenderer|MockObject */
    private $contentObjectRenderer;
    /** @var ApprenticeshipProcessor */
    private ApprenticeshipProcessor $subject;

    /**
     * @test
     */
    public function checkProcessAddsAddressDataIfFound(): void
    {
        $addressData = ['name' => 'Test Address', 'parent' => 123, 'uid' => 42];
        $expectedData = ['something' => 'else'];
        $expectedData['addressData'] = $addressData;

        $this->mockDatabase($addressData);
        $this->mockRequest(['tx_vdfrontend_recorddetail' => ['record' => '123']]);

        self::assertSame($expectedData, $this->subject->process($this->contentObjectRenderer, [], [], $expectedData));
    }

    /**
     * @test
     */
    public function checkProcessReturnsOriginalDataIfAddressRecordNotFound(): void
    {
        $this->mockDatabase(false);
        $this->mockRequest(['tx_vdfrontend_recorddetail' => ['record' => '123']]);

        self::assertSame(
            ['foo' => 'bar'],
            $this->subject->process($this->contentObjectRenderer, [], [], ['foo' => 'bar'])
        );
    }

    /**
     * @test
     */
    public function checkProcessReturnsOriginalDataIfNoRecordParameter(): void
    {
        $this->mockRequest([]);

        self::assertSame(
            ['foo' => 'bar'],
            $this->subject->process($this->contentObjectRenderer, [], [], ['foo' => 'bar'])
        );
    }

    /**
     * @test
     */
    public function checkProcessReturnsOriginalDataIfRecordParameterIsZero(): void
    {
        $this->mockRequest(['tx_vdfrontend_recorddetail' => ['record' => '0']]);

        self::assertSame(
            ['foo' => 'bar'],
            $this->subject->process($this->contentObjectRenderer, [], [], ['foo' => 'bar'])
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->connection = $this->createMock(ConnectionPool::class);
        $this->contentObjectRenderer = $this->createMock(ContentObjectRenderer::class);
        $this->subject = new ApprenticeshipProcessor($this->connection);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['TYPO3_REQUEST']);

        parent::tearDown();
    }

    private function mockDatabase($data): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAssociative')->willReturn($data);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('createNamedParameter')->willReturn('123');
        $queryBuilder->method('execute')->willReturn($result);
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('setRestrictions')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();

        $this->connection->method('getQueryBuilderForTable')->with('tt_address')->willReturn($queryBuilder);
    }

    private function mockRequest(array $queryParams): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn($queryParams);

        $GLOBALS['TYPO3_REQUEST'] = $request;
    }
}
