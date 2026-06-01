<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Tests\Unit\ViewHelpers;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdPrestations\Domain\Model\AccessModality;
use Vd\VdPrestations\ViewHelpers\AccessModalityViewHelper;

final class AccessModalityViewHelperTest extends UnitTestCase
{
    /**
     * Test external link handling
     */
    public function testRenderExternalLink(): void
    {
        // Mock the AccessModality object
        $accessModalityMock = $this->createMock(AccessModality::class);

        // Configure the mock to return true for isExternalLink and provide a URL
        $accessModalityMock->method('isExternalLink')->willReturn(true);
        $accessModalityMock->method('getUrl')->willReturn('https://external-link.com');

        // Instantiate the ViewHelper
        $viewHelper = new AccessModalityViewHelper();
        $viewHelper->setArguments(['accessModality' => $accessModalityMock]);

        // Test the render method
        $result = $viewHelper->render();

        // Assert the external URL is returned
        $this->assertSame('https://external-link.com', $result);
    }

    /**
     * Test internal link starting with 'http'
     */
    public function testRenderInternalLinkWithHttp(): void
    {
        // Mock the AccessModality object
        $accessModalityMock = $this->createMock(AccessModality::class);

        // Configure the mock to return false for isExternalLink and provide a URL starting with http
        $accessModalityMock->method('isExternalLink')->willReturn(false);
        $accessModalityMock->method('getUrl')->willReturn('http://internal-link.com');

        // Mock getenv() to return a domain
        putenv('TYPO3_EXT_VD_PRESTATIONS_ACCESS_MODALITY_URL=http://example.com');

        // Instantiate the ViewHelper
        $viewHelper = new AccessModalityViewHelper();
        $viewHelper->setArguments(['accessModality' => $accessModalityMock]);

        // Test the render method
        $result = $viewHelper->render();

        // Assert the URL with the full path is returned
        $this->assertSame('http://internal-link.com', $result);
    }

    /**
     * Test internal link without 'http'
     */
    public function testRenderInternalLinkWithoutHttp(): void
    {
        // Mock the AccessModality object
        $accessModalityMock = $this->createMock(AccessModality::class);

        // Configure the mock to return false for isExternalLink and provide a URL without http
        $accessModalityMock->method('isExternalLink')->willReturn(false);
        $accessModalityMock->method('getUrl')->willReturn('internal-link');

        // Mock getenv() to return a domain
        putenv('TYPO3_EXT_VD_PRESTATIONS_ACCESS_MODALITY_URL=https://example.com');

        // Instantiate the ViewHelper
        $viewHelper = new AccessModalityViewHelper();
        $viewHelper->setArguments(['accessModality' => $accessModalityMock]);

        // Test the render method
        $result = $viewHelper->render();

        // Assert the domain is prefixed to the internal URL with proper formatting
        $this->assertSame('https://example.com/internal-link', $result);
    }

    /**
     * Test internal link with 'PUBLIC' security level
     */
    public function testRenderInternalLinkWithSecurityLevelPublic(): void
    {
        // Mock the AccessModality object
        $accessModalityMock = $this->createMock(AccessModality::class);

        // Configure the mock to return false for isExternalLink and provide a URL without http
        $accessModalityMock->method('isExternalLink')->willReturn(false);
        $accessModalityMock->method('getUrl')->willReturn('internal-link');
        $accessModalityMock->method('getSecurityLevel')->willReturn('PUBLIC');

        // Mock getenv() to return a domain
        putenv('TYPO3_EXT_VD_PRESTATIONS_ACCESS_MODALITY_URL=https://example.com');

        // Instantiate the ViewHelper
        $viewHelper = new AccessModalityViewHelper();
        $viewHelper->setArguments(['accessModality' => $accessModalityMock]);

        // Test the render method
        $result = $viewHelper->render();

        // Assert the domain is prefixed with 'pub' for 'PUBLIC' security level
        $this->assertSame('https://example.com/pub/internal-link', $result);
    }
}
