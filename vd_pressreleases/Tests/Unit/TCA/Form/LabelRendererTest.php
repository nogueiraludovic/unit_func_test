<?php

namespace Vd\VdPressreleases\Tests\Unit\TCA\Form;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdPressreleases\TCA\Form\LabelRenderer;

class LabelRendererTest extends UnitTestCase
{
    /**
     * @test
     */
    public function forContactsSetsTitleWithAllFields()
    {
        $parameters = [
            'table' => 'tx_vdpressreleases_domain_model_contact',
            'row' => ['uid' => 1],
            'title' => ''
        ];

        $renderer = new class () extends LabelRenderer {
            protected function getRecord(string $table, int $uid): ?array
            {
                return [
                    'name' => 'Jane Doe',
                    'department' => 'Comms',
                    'service' => 'Newsroom',
                    'function' => 'Editor'
                ];
            }
        };

        $renderer->forContacts($parameters);

        $this->assertEquals('Jane Doe (Comms, Newsroom, Editor)', $parameters['title']);
    }

    /**
     * @test
     */
    public function forContactsSetsTitleWithOnlyName()
    {
        $parameters = [
            'table' => 'tx_vdpressreleases_domain_model_contact',
            'row' => ['uid' => 2],
            'title' => ''
        ];

        $renderer = new class () extends LabelRenderer {
            protected function getRecord(string $table, int $uid): ?array
            {
                return [
                    'name' => 'John Smith',
                    'department' => '',
                    'service' => '',
                    'function' => ''
                ];
            }
        };

        $renderer->forContacts($parameters);

        $this->assertEquals('John Smith', $parameters['title']);
    }

    /**
     * @test
     */
    public function forContactsSetsEmptyTitleIfRecordIsNull()
    {
        $parameters = [
            'table' => 'tx_vdpressreleases_domain_model_contact',
            'row' => ['uid' => 3],
            'title' => 'Should be cleared'
        ];

        $renderer = new class () extends LabelRenderer {
            protected function getRecord(string $table, int $uid): ?array
            {
                return null;
            }
        };

        $renderer->forContacts($parameters);

        $this->assertEquals('', $parameters['title']);
    }
}
