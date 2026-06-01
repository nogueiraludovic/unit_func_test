<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Tests\XmlSitemap;

use DateTime;
use Solarium\Client;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdSafarinet\XmlSitemap\SitemapDataProvider;

final class SitemapDataProviderTest extends UnitTestCase
{
    private SitemapDataProvider $sitemapDataProvider;

    protected function setUp(): void
    {
        parent::setUp();

        // Initialize SitemapDataProvider
        $this->sitemapDataProvider = $this->getMockBuilder(SitemapDataProvider::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getClient', 'getResult'])
            ->getMock();
    }

    public function testGenerateItemsWithSielCeType(): void
    {
        $config = [
            'type' => 'siel_ce',
            'url' => ['fieldToParameterMap' => ['key' => 'value']],
        ];

        $this->setProtectedProperty($this->sitemapDataProvider, 'config', $config);

        $mockDocument = (object)[
            'sielId_stringS' => '123',
            'changed' => '2023-09-01T10:00:00Z',
        ];

        $mockResult = [$mockDocument];
        $this->sitemapDataProvider
            ->method('getResult')
            ->with('type:siel_ce')
            ->willReturn($mockResult);

        $this->callProtectedMethod($this->sitemapDataProvider, 'generateItems');

        $items = $this->getProtectedProperty($this->sitemapDataProvider, 'items');

        $this->assertCount(1, $items);
        $this->assertEquals('123', $items[0]['data']['key']);
        $this->assertEquals(
            DateTime::createFromFormat('Y-m-d\TH:i:s\Z', '2023-09-01T10:00:00Z')->getTimestamp(),
            $items[0]['lastMod']
        );
        $this->assertEquals(0.5, $items[0]['priority']);
    }

    public function testGenerateItemsWithInvalidType(): void
    {
        $config = ['type' => 'unknown_type'];
        $this->setProtectedProperty($this->sitemapDataProvider, 'config', $config);

        $this->callProtectedMethod($this->sitemapDataProvider, 'generateItems');

        $items = $this->getProtectedProperty($this->sitemapDataProvider, 'items');

        $this->assertEmpty($items);
    }

    public function testGetClientReturnsConfiguredClient(): void
    {
        $client = $this->callProtectedMethod($this->sitemapDataProvider, 'getClient');
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testGetResultHandlesHttpException(): void
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->method('select')->willThrowException(new \Solarium\Exception\HttpException('Error occurred'));

        $this->sitemapDataProvider
            ->method('getClient')
            ->willReturn($mockClient);

        $result = $this->callProtectedMethod($this->sitemapDataProvider, 'getResult', ['test_query']);
        $this->assertNull($result);
    }

    private function setProtectedProperty(object $object, string $property, $value): void
    {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }

    private function getProtectedProperty(object $object, string $property)
    {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);

        return $reflection->getValue($object);
    }

    private function callProtectedMethod(object $object, string $method, array $args = [])
    {
        $reflection = new \ReflectionMethod($object, $method);
        $reflection->setAccessible(true);

        return $reflection->invokeArgs($object, $args);
    }
}
