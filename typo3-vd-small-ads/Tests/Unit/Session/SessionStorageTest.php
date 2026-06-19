<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\Tests\Unit\Session;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Vd\VdSmallAds\DataTransferObject\Demand;
use Vd\VdSmallAds\Session\SessionStorage;

final class SessionStorageTest extends TestCase
{
    private FrontendUserAuthentication&MockObject $frontendUser;
    private mixed $previousTsfe = null;
    private SessionStorage $subject;

    #[Test]
    public function getDelegatesToFrontendUserSession(): void
    {
        $this->frontendUser->expects(self::once())
            ->method('getKey')
            ->with('ses', 'my-key')
            ->willReturn('my-value');

        $this->assertSame('my-value', $this->subject->get('my-key'));
    }

    #[Test]
    public function getDemandUnserializesDemandObject(): void
    {
        $serializedDemand = (string)new Demand(' sale ', ' car ', ' query ');

        $this->frontendUser->expects(self::once())
            ->method('getKey')
            ->with('ses', 'search')
            ->willReturn($serializedDemand);

        $demand = $this->subject->getDemand('search');

        $this->assertInstanceOf(Demand::class, $demand);
        $this->assertSame('sale', $demand->getAdType());
        $this->assertSame('car', $demand->getObjectType());
        $this->assertSame('query', $demand->getQuery());
    }

    #[Test]
    public function hasReturnsTrueWhenSessionKeyExists(): void
    {
        $this->frontendUser->expects(self::once())
            ->method('getKey')
            ->with('ses', 'search')
            ->willReturn('serialized-value');

        $this->assertTrue($this->subject->has('search'));
    }

    #[Test]
    public function hasReturnsFalseWhenSessionKeyIsMissing(): void
    {
        $this->frontendUser->expects(self::once())
            ->method('getKey')
            ->with('ses', 'search')
            ->willReturn(null);

        $this->assertFalse($this->subject->has('search'));
    }

    #[Test]
    public function setDelegatesToFrontendUserSession(): void
    {
        $this->frontendUser->expects(self::once())
            ->method('setAndSaveSessionData')
            ->with('search', 'serialized-value');

        $this->subject->set('search', 'serialized-value');
    }

    #[Test]
    public function removeStoresNullForGivenKey(): void
    {
        $this->frontendUser->expects(self::once())
            ->method('setAndSaveSessionData')
            ->with('search', null);

        $this->subject->remove('search');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->previousTsfe = $GLOBALS['TSFE'] ?? null;
        $this->frontendUser = $this->createMock(FrontendUserAuthentication::class);

        $frontendController = $this->getMockBuilder(TypoScriptFrontendController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $frontendController->fe_user = $this->frontendUser;
        $GLOBALS['TSFE'] = $frontendController;

        $this->subject = new SessionStorage();
    }

    protected function tearDown(): void
    {
        if ($this->previousTsfe === null) {
            unset($GLOBALS['TSFE']);
        } else {
            $GLOBALS['TSFE'] = $this->previousTsfe;
        }

        parent::tearDown();
    }
}
