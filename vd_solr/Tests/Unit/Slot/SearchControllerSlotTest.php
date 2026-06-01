<?php

declare(strict_types=1);

namespace Vd\VdSolr\Tests\Unit\Slot;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Vd\VdSolr\Slot\SearchControllerSlot;

class SearchControllerSlotTest extends TestCase
{
    protected function setUp(): void
    {
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->method('getParsedBody')->willReturn([]);
        $requestMock->method('getQueryParams')->willReturn([
            'tx_solr' => [
                'filter' => ['creationDateRange:202301010000-202312310000']
            ]
        ]);

        $GLOBALS['TYPO3_REQUEST'] = $requestMock;
    }

    public function testBeforeSearchSlotWithCreationDateRange(): void
    {
        $slot = new SearchControllerSlot();

        $arguments = [
            'from' => [],
            'to' => [],
        ];

        [$result] = $slot->beforeSearchSlot($arguments);

        $this->assertArrayHasKey('from', $result);
        $this->assertArrayHasKey('to', $result);
        $this->assertSame('01', $result['from'][0]);
        $this->assertSame('12', $result['to'][1]);
    }
}
