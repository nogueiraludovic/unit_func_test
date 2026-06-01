<?php

namespace Vd\VdPressreleases\Tests\Unit\Domain\Model;

use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Vd\VdPressreleases\Domain\Model\Contact;
use Vd\VdPressreleases\Domain\Model\PressRelease;

class ContactTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($GLOBALS['TSFE']);

        parent::tearDown();
    }

    /**
     * @test
     */
    public function renderReturnsFormattedStringWithEmailAndPhone()
    {
        $contact = (new Contact())
            ->setDepartment('Comms')
            ->setName('Jane Doe')
            ->setFunction('Editor')
            ->setService('Newsroom')
            ->setEmail('jane@example.com')
            ->setPhone('+43 123 456');

        // Mock PressRelease
        $pressReleaseMock = $this->createMock(PressRelease::class);
        $pressReleaseMock->method('hasAnonymize')->willReturn(false);

        // Mock TSFE and typoLink
        $cObjMock = $this->createMock(ContentObjectRenderer::class);
        $cObjMock->method('typoLink')->willReturn('<a href="mailto:jane@example.com">jane@example.com</a>');

        $tsfeMock = $this->createMock(TypoScriptFrontendController::class);
        $tsfeMock->cObj = $cObjMock;

        $GLOBALS['TSFE'] = $tsfeMock;

        $output = $contact->render($pressReleaseMock);

        $this->assertStringContainsString('Comms', $output);
        $this->assertStringContainsString('Jane Doe', $output);
        $this->assertStringContainsString('Editor', $output);
        $this->assertStringContainsString('Newsroom', $output);
        $this->assertStringContainsString('<a href="mailto:jane@example.com">jane@example.com</a>', $output);
        $this->assertStringContainsString('<a class="phone-link" href="tel:+43123456"', $output); // spaces removed
    }
}
