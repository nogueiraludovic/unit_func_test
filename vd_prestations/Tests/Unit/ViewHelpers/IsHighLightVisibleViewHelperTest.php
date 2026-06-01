<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Tests\Unit\ViewHelpers;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider;
use Vd\VdPrestations\Domain\Model\AccessModality;
use Vd\VdPrestations\ViewHelpers\IsHighLightVisibleViewHelper;

final class IsHighLightVisibleViewHelperTest extends UnitTestCase
{
    /**
     * @var IsHighLightVisibleViewHelper
     */
    protected $viewHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->viewHelper = $this->getAccessibleMock(
            IsHighLightVisibleViewHelper::class,
            ['dummy'], // no methods need to be mocked
            [], // no constructor args
            '', // no mock class name
            true // call original constructor
        );
    }

    protected function tearDown(): void
    {
        unset($this->viewHelper);
        parent::tearDown();
    }

    /**
     * @test
     * @dataProvider visibilityDataProvider
     */
    public function renderReturnsCorrectVisibility(
        string $additionalInformations,
        string $requiredDocuments,
        string $cost,
        string $averageDelay,
        bool $expectedResult
    ): void {
        // Create a mock AccessModality object
        $accessModality = $this->createMock(AccessModality::class);

        // Configure the mock methods
        $accessModality->method('getAdditionalInformations')
            ->willReturn($additionalInformations);

        $accessModality->method('getRequiredDocuments')
            ->willReturn($requiredDocuments);

        $accessModality->method('getCost')
            ->willReturn($cost);

        $accessModality->method('getAverageDelay')
            ->willReturn($averageDelay);

        // Create a variable container and add our accessModality
        $variableContainer = new StandardVariableProvider(['accessModality' => $accessModality]);

        // Assign the variable container to the view helper using reflection
        $this->viewHelper->_set('templateVariableContainer', $variableContainer);

        // Test the render method
        $result = $this->viewHelper->render();

        // Assert the expected result
        self::assertEquals($expectedResult, $result);
    }

    /**
     * Data provider for renderReturnsCorrectVisibility
     */
    public function visibilityDataProvider(): array
    {
        return [
            'All fields empty' => [
                '',   // additionalInformations
                '',   // requiredDocuments
                '',   // cost
                '',    // averageDelay
                false    // expectedResult
            ],
            'Only additional informations' => [
                'Some info', // additionalInformations
                '',        // requiredDocuments
                '',        // cost
                '',        // averageDelay
                true         // expectedResult
            ],
            'Only required documents' => [
                '',            // additionalInformations
                'Document list', // requiredDocuments
                '',            // cost
                '',            // averageDelay
                true             // expectedResult
            ],
            'Only cost' => [
                '',         // additionalInformations
                '',        // requiredDocuments
                '100 EUR',   // cost
                '',        // averageDelay
                true          // expectedResult
            ],
            'Only average delay' => [
                '',           // additionalInformations
                '',           // requiredDocuments
                '',           // cost
                '2 weeks',      // averageDelay
                true           // expectedResult
            ],
            'All fields filled' => [
                'Info',       // additionalInformations
                'Documents',   // requiredDocuments
                '50 EUR',      // cost
                '1 week',      // averageDelay
                true           // expectedResult
            ],
            'Mixed fields filled 1' => [
                'Info',       // additionalInformations
                '',         // requiredDocuments
                '50 EUR',     // cost
                '',         // averageDelay
                true          // expectedResult
            ],
            'Mixed fields filled 2' => [
                '',         // additionalInformations
                'Documents',  // requiredDocuments
                '',         // cost
                '1 week',    // averageDelay
                true         // expectedResult
            ],
            'Empty strings should still count as filled' => [
                '',           // additionalInformations (empty string)
                '',         // requiredDocuments
                '',         // cost
                '',         // averageDelay
                false          // expectedResult
            ],
        ];
    }
}
