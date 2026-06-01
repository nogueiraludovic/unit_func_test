<?php

declare(strict_types=1);

namespace Vd\VdMunicipalities\Tests\Functional\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use Vd\VdMunicipalities\Domain\Repository\MunicipalityRepository;

class MunicipalityRepositoryTest extends FunctionalTestCase
{
    protected MunicipalityRepository $municipalityRepository;

    protected $coreExtensionsToLoad = ['extbase', 'fluid'];
    protected $testExtensionsToLoad = ['typo3conf/ext/vd_municipalities'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/DefaultDistrictAndMunicipalities.csv');
        $this->municipalityRepository = GeneralUtility::makeInstance(MunicipalityRepository::class);
    }

    public function testFindByDistrictReturnsExpectedMunicipalities(): void
    {
        $districtUid = 1;
        $results = $this->municipalityRepository->findByDistrict($districtUid);

        self::assertNotEmpty($results);
        foreach ($results as $municipality) {
            self::assertSame($districtUid, $municipality->getIdDistrict());
        }
    }

    public function testSearchFindsMunicipalityByName(): void
    {
        $term = 'bern';
        $results = $this->municipalityRepository->search($term);

        self::assertNotEmpty($results);
        foreach ($results as $municipality) {
            $name = strtolower($municipality->getNameLower());
            self::assertStringContainsString($term, $name);
        }
    }
}
