<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Tests\Unit\ViewHelpers;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider;
use Vd\VdPrestations\Domain\Model\Prestation;
use Vd\VdPrestations\ViewHelpers\IsInformationVisibleViewHelper;

final class IsInformationVisibleViewHelperTest extends UnitTestCase
{
    /**
     * @var IsInformationVisibleViewHelper
     */
    protected $viewHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->viewHelper = $this->getAccessibleMock(
            IsInformationVisibleViewHelper::class,
            ['dummy'],
            [],
            '',
            true
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
        int $legalReferencesCount,
        int $relatedPagesCount,
        int $relatedPrestationsCount,
        bool $expectedResult
    ): void {
        // Create a mock Prestation object
        $prestation = $this->createMock(Prestation::class);

        // Configure the mock methods to return ObjectStorage instances
        $prestation->method('getLegalReferences')
            ->willReturn($this->createObjectStorageMock($legalReferencesCount));

        $prestation->method('getRelatedPages')
            ->willReturn($this->createObjectStorageMock($relatedPagesCount));

        $prestation->method('getRelatedPrestations')
            ->willReturn($this->createObjectStorageMock($relatedPrestationsCount));

        // Create a variable container and add our prestation
        $variableContainer = new StandardVariableProvider(['prestation' => $prestation]);

        // Assign the variable container to the view helper using reflection
        $this->viewHelper->_set('templateVariableContainer', $variableContainer);

        // Test the render method
        $result = $this->viewHelper->render();

        // Assert the expected result
        self::assertEquals($expectedResult, $result);
    }

    /**
     * Creates an ObjectStorage mock with a specific count
     */
    protected function createObjectStorageMock(int $count): ObjectStorage
    {
        $objectStorage = $this->createMock(ObjectStorage::class);
        $objectStorage->method('count')->willReturn($count);
        return $objectStorage;
    }

    /**
     * Data provider for renderReturnsCorrectVisibility
     */
    public function visibilityDataProvider(): array
    {
        return [
            'All counts zero' => [
                0, 0, 0, false
            ],
            'Only legal references' => [
                1, 0, 0, true
            ],
            'Only related pages' => [
                0, 1, 0, true
            ],
            'Only related prestations' => [
                0, 0, 1, true
            ],
            'All counts non-zero' => [
                1, 1, 1, true
            ],
            'Legal references and related pages' => [
                2, 1, 0, true
            ],
        ];
    }
}
