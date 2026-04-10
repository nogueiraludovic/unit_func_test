<?php

declare(strict_types=1);

namespace Vd\VdCore\Authentication;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class AuthenticationTraitTest extends FunctionalTestCase
{
    #[Test]
    public function getFakeAdminUser(): void
    {
        $random = $this->createMock(Random::class);
        $random->method('generateRandomHexString')->willReturn('abcdef1234567890abcdef1234567890');

        $subject = new class (new BackendUserAuthentication(), $random) {
            use AuthenticationTrait;
        };

        $result = $subject->getFakeAdminUser('john');

        $this->assertSame('abcdef1234567890abcdef1234567890', $result->getSession()->getIdentifier());
        $this->assertSame(true, $result->user['admin']);
        $this->assertSame(0, $result->user['uid']);
        $this->assertSame('john', $result->user['username']);
        $this->assertSame(0, $result->workspace);
        $this->assertSame($result, $GLOBALS['BE_USER']);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['BE_USER']);

        parent::tearDown();
    }
}
