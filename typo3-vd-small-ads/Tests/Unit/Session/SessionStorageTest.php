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

use function serialize;

final class SessionStorageTest extends TestCase
{
    private MockObject $feUser;
    private SessionStorage $storage;

    #[Test]
    public function hasReturnsFalseWhenKeyIsAbsent(): void
    {
        $this->feUser->method('getKey')->with('ses', 'my_key')->willReturn(null);

        $this->assertFalse($this->storage->has('my_key'));
    }

    #[Test]
    public function hasReturnsTrueWhenKeyIsPresent(): void
    {
        $this->feUser->method('getKey')->with('ses', 'any_key')->willReturn('stored_value');

        $this->assertTrue($this->storage->has('any_key'));
    }

    #[Test]
    public function getReturnsValueFromSession(): void
    {
        $this->feUser->method('getKey')->with('ses', 'the_key')->willReturn('the_value');

        $this->assertSame('the_value', $this->storage->get('the_key'));
    }

    #[Test]
    public function setCallsSetAndSaveSessionData(): void
    {
        $this->feUser->expects($this->once())
            ->method('setAndSaveSessionData')
            ->with('my_key', 'my_value');

        $this->storage->set('my_key', 'my_value');
    }

    #[Test]
    public function removeCallsSetWithNull(): void
    {
        $this->feUser->expects($this->once())
            ->method('setAndSaveSessionData')
            ->with('remove_key', null);

        $this->storage->remove('remove_key');
    }

    #[Test]
    public function getDemandDeserializesStoredDemand(): void
    {
        $original = new Demand('sale', 'house', 'Geneva');
        $this->feUser->method('getKey')->with('ses', 'session_id')->willReturn(serialize($original));

        $result = $this->storage->getDemand('session_id');

        $this->assertInstanceOf(Demand::class, $result);
        $this->assertSame('sale', $result->getAdType());
        $this->assertSame('house', $result->getObjectType());
        $this->assertSame('Geneva', $result->getQuery());
    }

    #[Test]
    public function getDemandReturnsIncompleteClassForDisallowedObject(): void
    {
        $notAllowed = new \stdClass();
        $notAllowed->foo = 'bar';
        $this->feUser->method('getKey')->with('ses', 'session_id')->willReturn(serialize($notAllowed));

        $result = $this->storage->getDemand('session_id');

        $this->assertFalse($result instanceof Demand);
        $this->assertIsObject($result);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->feUser = $this->createMock(FrontendUserAuthentication::class);
        $tsfe = $this->createMock(TypoScriptFrontendController::class);
        $tsfe->fe_user = $this->feUser;
        $GLOBALS['TSFE'] = $tsfe;

        $this->storage = new SessionStorage();
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['TSFE']);

        parent::tearDown();
    }
}
