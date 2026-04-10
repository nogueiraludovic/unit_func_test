<?php

declare(strict_types=1);

namespace Vd\VdCore\DataProcessing;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class TcaGroupProcessorTest extends FunctionalTestCase
{
    protected ContentObjectRenderer $cObj;
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/vd_core'
    ];

    #[Test]
    public function processResolvesRelatedRecords(): void
    {
        $connectionPool = $this->get(ConnectionPool::class);
        $connection = $connectionPool->getConnectionForTable('tt_content');

        $connection->insert('tt_content', [
            'uid' => 100,
            'pid' => 1,
            'header' => 'Record A'
        ]);

        $connection->insert('tt_content', [
            'uid' => 200,
            'pid' => 1,
            'header' => 'Record B'
        ]);

        $this->cObj->start([
            'related' => 'tt_content_100,tt_content_200'
        ]);

        $result = (new TcaGroupProcessor($connectionPool))->process(
            $this->cObj,
            [],
            [
                'field' => 'related',
                'allowed' => 'tt_content',
                'as' => 'items'
            ],
            []
        );

        $this->assertArrayHasKey('items', $result);
        $this->assertCount(2, $result['items']);
        $this->assertSame('Record A', $result['items'][0]['data']['header']);
        $this->assertSame('Record B', $result['items'][1]['data']['header']);
    }

    #[Test]
    public function processReturnsEarlyWhenIfConditionFails(): void
    {
        $this->cObj->start(['related' => 'tt_content_100']);

        $result = (new TcaGroupProcessor($this->get(ConnectionPool::class)))->process(
            $this->cObj,
            [],
            [
                'field' => 'related',
                'allowed' => 'tt_content',
                'if.' => ['equals' => '0']
            ],
            ['existing' => 'value']
        );

        $this->assertSame(['existing' => 'value'], $result);
    }

    #[Test]
    public function processReturnsEarlyWhenFieldIsEmpty(): void
    {
        $this->cObj->start(['related' => '']);

        $result = (new TcaGroupProcessor($this->get(ConnectionPool::class)))->process(
            $this->cObj,
            [],
            [
                'field' => 'related',
                'allowed' => 'tt_content'
            ],
            ['existing' => 'value']
        );

        $this->assertSame(['existing' => 'value'], $result);
    }

    #[Test]
    public function processReturnsEarlyWhenNoRecordsFound(): void
    {
        $this->cObj->start([
            'related' => 'tt_content_9999'
        ]);

        $result = (new TcaGroupProcessor($this->get(ConnectionPool::class)))->process(
            $this->cObj,
            [],
            [
                'field' => 'related',
                'allowed' => 'tt_content',
                'as' => 'items'
            ],
            ['existing' => 'value']
        );

        $this->assertSame(['existing' => 'value'], $result);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->cObj = new ContentObjectRenderer();
    }
}
