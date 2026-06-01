<?php

declare(strict_types=1);

namespace Vd\VdSolr\Tests\Slot;

use ApacheSolrForTypo3\Solr\IndexQueue\Item;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdSolr\Slot\IndexServiceSlot;

final class IndexServiceSlotTest extends UnitTestCase
{
    public function testBeforeIndexItemSlotReturnsUnmodifiedForNonMatchingType(): void
    {
        $itemMock = $this->createMock(Item::class);
        $itemMock->method('getType')->willReturn('different_type');

        $slot = new IndexServiceSlot();
        $result = $slot->beforeIndexItemSlot($itemMock, null, '');

        $this->assertSame([$itemMock, null, ''], $result);
    }

    public function testBeforeIndexItemSlotReturnsUnmodifiedForNonMatchingRecordType(): void
    {
        $itemMock = $this->createMock(Item::class);
        $itemMock->method('getType')->willReturn('tx_news_domain_model_news');
        $itemMock->method('getRecord')->willReturn(['type' => 2]);

        $slot = new IndexServiceSlot();
        $result = $slot->beforeIndexItemSlot($itemMock, null, '');

        $this->assertSame([$itemMock, null, ''], $result);
    }

    public function testBeforeIndexItemSlotModifiesItemForMatchingRecord(): void
    {
        $itemMock = $this->createMock(Item::class);
        $itemMock->method('getType')->willReturn('tx_news_domain_model_news');
        $itemMock->method('getRecord')->willReturn([
            'type' => 3,
            'selected_article' => 123,
        ]);

        $aliasData = [
            'uid' => 456,
            'author' => 'John Doe',
            'author_email' => 'john.doe@example.com',
            'bodytext' => 'Sample body text',
            'categories' => 'Category1, Category2',
            'datetime' => '2025-01-01',
            'keywords' => 'keyword1, keyword2',
            'path_segment' => 'path-segment',
            'related_files' => 'file1, file2',
            'tags' => 'tag1, tag2',
            'teaser' => 'Sample teaser',
            'title' => 'Sample Title',
        ];

        $slot = $this->getMockBuilder(IndexServiceSlot::class)
            ->onlyMethods(['getAlias'])
            ->getMock();

        $slot->method('getAlias')->willReturn($aliasData);

        $itemMock->expects($this->once())
            ->method('setRecord')
            ->with($this->callback(static function ($record) use ($aliasData) {
                return $record['alias'] === $aliasData['uid'] &&
                    $record['aliasurl'] === 't3://record?identifier=news&uid=' . $aliasData['uid'] &&
                    $record['author'] === $aliasData['author'];
            }));

        $result = $slot->beforeIndexItemSlot($itemMock, null, '');

        $this->assertSame([$itemMock, null, ''], $result);
    }
}
