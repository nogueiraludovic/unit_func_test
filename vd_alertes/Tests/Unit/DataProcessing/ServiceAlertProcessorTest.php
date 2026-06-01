<?php

declare(strict_types=1);

namespace Vd\VdAlertes\Tests\Unit\DataProcessing;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdAlertes\DataProcessing\ServiceAlertProcessor;
use Vd\VdAlertes\Domain\Repository\ServiceAlertRepository;

final class ServiceAlertProcessorTest extends UnitTestCase
{
    /** @var MockObject|TypoScriptFrontendController */
    private $frontendController;
    /** @var MockObject|ServiceAlertRepository */
    private $serviceAlertRepository;
    /** @var ServiceAlertProcessor */
    private ServiceAlertProcessor $subject;

    /**
     * @test
     */
    public function checkProcessWithNoAlerts(): void
    {
        $this->frontendController->rootLine = [['uid' => 1], ['uid' => 2]];
        $this->serviceAlertRepository->method('__call')->with('findOneByPid', self::anything())->willReturn(null);

        self::assertArrayNotHasKey(
            'serviceAlerts',
            $this->subject->process($this->createMock(ContentObjectRenderer::class), [], [], [])
        );
    }

    protected function setUp(): void
    {
        $this->frontendController = $this->createMock(TypoScriptFrontendController::class);
        $this->serviceAlertRepository = $this
            ->getMockBuilder(ServiceAlertRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['__call'])
            ->getMock();
        $this->subject = new ServiceAlertProcessor($this->serviceAlertRepository);

        $GLOBALS['TSFE'] = $this->frontendController;
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['TSFE']);

        parent::tearDown();
    }
}
