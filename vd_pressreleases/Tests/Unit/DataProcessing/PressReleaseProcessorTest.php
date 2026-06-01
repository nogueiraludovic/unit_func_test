<?php

namespace Vd\VdPressreleases\Tests\Unit\DataProcessing;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdPressreleases\DataProcessing\PressReleaseProcessor;
use Vd\VdPressreleases\Domain\Model\PressRelease;
use Vd\VdPressreleases\Domain\Repository\PressReleaseRepository;

class PressReleaseProcessorTest extends UnitTestCase
{
    protected PressReleaseProcessor $processor;
    protected $pressReleaseRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pressReleaseRepositoryMock = $this->createMock(PressReleaseRepository::class);
        $this->processor = new PressReleaseProcessor($this->pressReleaseRepositoryMock);
    }

    public function testProcessReturnsProcessedDataWithPressRelease()
    {
        $pressReleaseMock = $this->createMock(PressRelease::class);

        $this->pressReleaseRepositoryMock
            ->method('findByUid')
            ->with(123)
            ->willReturn($pressReleaseMock); // ✅ Correct type

        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->method('getQueryParams')->willReturn([
            'tx_vdpressreleases_pressrelease' => [
                'pressRelease' => '123',
                'pressRelease_preview' => null
            ]
        ]);

        $GLOBALS['TYPO3_REQUEST'] = $requestMock;

        $result = $this->processor->process(
            $this->createMock(ContentObjectRenderer::class),
            [],
            [],
            []
        );

        $this->assertArrayHasKey('pressReleaseData', $result);
        $this->assertSame($pressReleaseMock, $result['pressReleaseData']); // ✅ Match the object
    }

    /**
     * @test
     */
    public function processReturnsUnchangedDataIfUidIsZero()
    {
        $requestMock = $this->createMock(ServerRequestInterface::class);
        $requestMock->method('getQueryParams')->willReturn([
            'tx_vdpressreleases_pressrelease' => [
                'pressRelease' => '0',
                'pressRelease_preview' => null
            ]
        ]);

        $GLOBALS['TYPO3_REQUEST'] = $requestMock;

        $result = $this->processor->process(
            $this->createMock(ContentObjectRenderer::class),
            [],
            [],
            ['existing' => 'data']
        );

        $this->assertSame(['existing' => 'data'], $result);
    }
}
