<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Tests\DataProcessing;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdSafarinet\DataProcessing\SielProcessor;
use Vd\VdSafarinet\Service\SielService;

final class SielProcessorTest extends UnitTestCase
{
    private FrontendInterface $cacheFrontendMock;
    private CacheManager $cacheManagerMock;
    private ContentObjectRenderer $cObjMock;
    private ServerRequestInterface $requestMock;
    protected $resetSingletonInstances = true;
    private SielProcessor $sielProcessor;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock ContentObjectRenderer
        $this->cObjMock = $this->createMock(ContentObjectRenderer::class);

        // Mock CacheManager and CacheFrontend
        $this->cacheManagerMock = $this->createMock(CacheManager::class);
        $this->cacheFrontendMock = $this->createMock(FrontendInterface::class);
        $this->cacheManagerMock->method('hasCache')->with('vd_safarinet')->willReturn(true);
        $this->cacheManagerMock->method('getCache')->with('vd_safarinet')->willReturn($this->cacheFrontendMock);

        // Use setSingletonInstance for CacheManager
        GeneralUtility::setSingletonInstance(CacheManager::class, $this->cacheManagerMock);

        // Mock ServerRequestInterface
        $this->requestMock = $this->createMock(ServerRequestInterface::class);

        // Assign the SielProcessor
        $this->sielProcessor = $this->getMockBuilder(SielProcessor::class)
            ->onlyMethods(['getRequest'])
            ->getMock();
        $this->sielProcessor->method('getRequest')->willReturn($this->requestMock);
    }

    public function testProcessReturnsCachedData(): void
    {
        $processedData = [];

        // Mock request query params
        $this->requestMock->method('getQueryParams')->willReturn([
            'tx_vdsafarinet_safarinet' => [
                'action' => 'gcMeetingShow',
                'meetingId' => '123',
            ],
        ]);

        // Mock cache hit
        $this->cacheFrontendMock->method('get')->with(sha1('meeting_gc123'))->willReturn(['cachedData']);

        $result = $this->sielProcessor->process(
            $this->cObjMock,
            [],
            [],
            $processedData
        );

        $this->assertArrayHasKey('sielMeetingGcData', $result);
        $this->assertEquals(['cachedData'], $result['sielMeetingGcData']);
    }

    public function testProcessFetchesAndCachesData(): void
    {
        $processedData = [];

        // Mock request query params
        $this->requestMock->method('getQueryParams')->willReturn([
            'tx_vdsafarinet_safarinet' => [
                'action' => 'gcPointShow',
                'pointId' => '456',
            ],
        ]);

        // Mock cache miss
        $this->cacheFrontendMock->method('get')->with(sha1('point_gc456'))->willReturn(false);

        // Mock SielService call
        $mockSielService = $this->createMock(SielService::class);
        $mockSielService->method('fetchGreatCouncilPoint')->with('456')->willReturn(['fetchedData']);

        // Inject the mock directly into the SielProcessor instance
        GeneralUtility::addInstance(SielService::class, $mockSielService);

        $result = $this->sielProcessor->process(
            $this->cObjMock,
            [],
            [],
            $processedData
        );

        $this->assertArrayHasKey('sielPointGcData', $result);
        $this->assertEquals(['fetchedData'], $result['sielPointGcData']);
    }

    public function testProcessHandlesMissingArguments(): void
    {
        $processedData = [];

        // Mock request with an empty tx_vdsafarinet_safarinet key
        $this->requestMock->method('getQueryParams')->willReturn([
            'tx_vdsafarinet_safarinet' => null
        ]);

        $result = $this->sielProcessor->process(
            $this->cObjMock,
            [],
            [],
            $processedData
        );

        $this->assertEquals($processedData, $result); // No modifications to processedData
    }

    public function testProcessReturnsEmptyForUnrecognizedAction(): void
    {
        $processedData = [];

        // Mock request query params with unrecognized action
        $this->requestMock->method('getQueryParams')->willReturn([
            'tx_vdsafarinet_safarinet' => [
                'action' => 'unknownAction',
            ],
        ]);

        $result = $this->sielProcessor->process(
            $this->cObjMock,
            [],
            [],
            $processedData
        );

        $this->assertEquals($processedData, $result); // No modifications to processedData
    }
}
