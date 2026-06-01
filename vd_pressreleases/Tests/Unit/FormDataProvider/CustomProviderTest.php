<?php

namespace Vd\VdPressreleases\Tests\Unit\FormDataProvider;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdPressreleases\FormDataProvider\CustomProvider;

class CustomProviderTest extends UnitTestCase
{
    protected CustomProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = new CustomProvider();
    }

    /**
     * @test
     */
    public function addDataSetsMinItemsAndLabelForRelayType()
    {
        $GLOBALS['LANG'] = $this->createMock(LanguageService::class);
        $GLOBALS['LANG']->method('sL')->willReturn('Translated Label');

        $input = [
            'tableName' => 'tx_vdpressreleases_domain_model_pressrelease',
            'databaseRow' => ['type' => [7], 'date_time' => 0],
            'processedTca' => ['columns' => ['forcedpdf_file' => ['config' => []]]]
        ];

        $result = $this->provider->addData($input);

        $this->assertEquals(1, $result['processedTca']['columns']['forcedpdf_file']['config']['minitems']);
        $this->assertEquals('Translated Label', $result['processedTca']['columns']['forcedpdf_file']['label']);
        $this->assertGreaterThan(0, $result['databaseRow']['date_time']);
    }
}
