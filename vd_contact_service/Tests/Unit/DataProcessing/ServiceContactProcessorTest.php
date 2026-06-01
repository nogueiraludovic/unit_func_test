<?php

declare(strict_types=1);

namespace Vd\VdContactService\Tests\Unit\DataProcessing;

use PHPUnit\Framework\MockObject\MockObject;
use ReflectionMethod;
use stdClass;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Page\PageLayoutResolver;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdContactService\DataProcessing\ServiceContactProcessor;
use Vd\VdContactService\Domain\Repository\ServiceContactRepository;

final class ServiceContactProcessorTest extends UnitTestCase
{
    /** @var ContentObjectRenderer|MockObject */
    private $cObj;
    /** @var MockObject|TypoScriptFrontendController */
    private $frontendController;
    /** @var MockObject|PageLayoutResolver */
    private $pageLayoutResolver;
    /** @var MockObject|ServiceContactRepository */
    private $serviceContactRepository;
    /** @var ServiceContactProcessor */
    private ServiceContactProcessor $subject;
    /** @var MockObject|StandaloneView */
    private $view;
    /** @var MockObject|UriBuilder */
    private $uriBuilder;

    /**
     * @dataProvider serviceContactProvider
     * @test
     */
    public function checkGetServiceContactUidReturnsCorrectValue(
        array $page,
        array $rootLine,
        string $layout,
        int $expected
    ): void {
        $this->frontendController->page = $page;
        $this->frontendController->rootLine = $rootLine;
        $this->pageLayoutResolver->method('getLayoutForPage')->willReturn($layout);

        $subjectMethodReflection = new ReflectionMethod($this->subject, 'getServiceContactUid');
        $subjectMethodReflection->setAccessible(true);

        self::assertSame($expected, $subjectMethodReflection->invoke($this->subject));
    }

    /**
     * @test
     */
    public function checkProcessReturnsEarlyIfContactNotFound(): void
    {
        $subject = $this
            ->getMockBuilder(ServiceContactProcessor::class)
            ->setConstructorArgs([$this->pageLayoutResolver, $this->serviceContactRepository, $this->view, $this->uriBuilder])
            ->onlyMethods(['getServiceContactUid'])
            ->getMock();
        $subject->method('getServiceContactUid')->willReturn(42);

        $this->serviceContactRepository->method('findByUid')->with(42)->willReturn(null);

        self::assertSame(
            ['foo' => 'bar', 'contactFormLink' => 'https://example.test/contact'],
            $subject->process($this->cObj, [], [], ['foo' => 'bar'])
        );
    }

    /**
     * @test
     */
    public function checkProcessReturnsEarlyIfUidIsZero(): void
    {
        $subject = $this
            ->getMockBuilder(ServiceContactProcessor::class)
            ->setConstructorArgs([$this->pageLayoutResolver, $this->serviceContactRepository, $this->view, $this->uriBuilder])
            ->onlyMethods(['getServiceContactUid'])
            ->getMock();
        $subject->method('getServiceContactUid')->willReturn(0);

        self::assertSame(
            ['foo' => 'bar', 'contactFormLink' => 'https://example.test/contact'],
            $subject->process($this->cObj, [], [], ['foo' => 'bar'])
        );
    }

    public static function serviceContactProvider(): array
    {
        return [
            'Hidden on current page' => [
                ['service_contact' => 999, 'service_contact_hidden' => true],
                [
                    ['service_contact' => 0, 'service_contact_hidden_subpages' => false],
                    ['service_contact' => 456, 'service_contact_hidden_subpages' => false]
                ],
                'pagets__2_col',
                0
            ],
            'Inherit but hidden in rootline' => [
                ['service_contact' => 0, 'service_contact_hidden' => false],
                [
                    ['service_contact' => 0, 'service_contact_hidden_subpages' => false],
                    ['service_contact' => 456, 'service_contact_hidden_subpages' => true]
                ],
                'pagets__2_col',
                0
            ],
            'Inherit from rootLine' => [
                ['service_contact' => 0, 'service_contact_hidden' => false],
                [
                    ['service_contact' => 0, 'service_contact_hidden_subpages' => false],
                    ['service_contact' => 456, 'service_contact_hidden_subpages' => false]
                ],
                'pagets__2_col',
                456
            ],
            'Layout not allowed' => [
                ['service_contact' => 999, 'service_contact_hidden' => false],
                [
                    ['service_contact' => 0, 'service_contact_hidden_subpages' => false],
                    ['service_contact' => 456, 'service_contact_hidden_subpages' => false]
                ],
                'pagets__not_allowed',
                0
            ],
            'Service contact on current page' => [
                ['service_contact' => 123, 'service_contact_hidden' => false],
                [
                    ['service_contact' => 0, 'service_contact_hidden_subpages' => false],
                    ['service_contact' => 456, 'service_contact_hidden_subpages' => false]
                ],
                'pagets__2_col',
                123
            ]
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->cObj = $this->createMock(ContentObjectRenderer::class);
        $this->frontendController = $this->createMock(TypoScriptFrontendController::class);
        $this->pageLayoutResolver = $this->createMock(PageLayoutResolver::class);
        $this->serviceContactRepository = $this->createMock(ServiceContactRepository::class);
        $this->view = $this->createMock(StandaloneView::class);
        $this->uriBuilder = $this->createMock(UriBuilder::class);

        $tmpl = new stdClass();
        $tmpl->setup = [
            'plugin.' => [
                'tx_vdcontactservice.' => [
                    'settings.' => [
                        'contactFormPid' => 321
                    ]
                ]
            ]
        ];

        $this->frontendController->tmpl = $tmpl;
        $this->uriBuilder = $this->createMock(UriBuilder::class);
        $this->uriBuilder->method('buildFrontendUri')->willReturn('https://example.test/contact');
        $this->uriBuilder->method('reset')->willReturn($this->uriBuilder);
        $this->uriBuilder->method('setTargetPageUid')->with(321)->willReturn($this->uriBuilder);

        $GLOBALS['TSFE'] = $this->frontendController;

        $this->subject = new ServiceContactProcessor(
            $this->pageLayoutResolver,
            $this->serviceContactRepository,
            $this->view,
            $this->uriBuilder
        );
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['TSFE']);

        parent::tearDown();
    }
}
