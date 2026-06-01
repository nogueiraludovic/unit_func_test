<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\Tests\Unit\ViewHelpers;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use Vd\VdPressreleases\Domain\Model\Contact;
use Vd\VdPressreleases\Domain\Model\PressRelease;
use Vd\VdPressreleases\ViewHelpers\ContactViewHelper;

final class ContactViewHelperTest extends UnitTestCase
{
    public function testRenderStaticCallsRenderOnContactWithPressRelease(): void
    {
        // Mock Contact object
        $contactMock = $this->createMock(Contact::class);
        $pressReleaseMock = $this->createMock(PressRelease::class);

        // Expected result when render is called on Contact with PressRelease
        $expectedResult = 'Rendered Content';

        // Mock the render method on Contact to return the expected result
        $contactMock->method('render')->with($pressReleaseMock)->willReturn($expectedResult);

        // Create arguments array for renderStatic
        $arguments = [
            'contact' => $contactMock,
            'pressRelease' => $pressReleaseMock
        ];

        // Mock RenderingContext (not used in this simple test, but required by the method signature)
        $renderingContextMock = $this->createMock(RenderingContextInterface::class);

        // Call the static render method
        $result = ContactViewHelper::renderStatic($arguments, static function () {
            return ''; // Not used in this test, so we just return an empty string
        }, $renderingContextMock);

        // Assert that the result is as expected
        $this->assertSame($expectedResult, $result);
    }
}
