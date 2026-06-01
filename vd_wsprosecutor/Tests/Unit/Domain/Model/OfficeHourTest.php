<?php

declare(strict_types=1);

namespace Vd\VdWsprosecutor\Tests\Unit\Domain\Model;

use DateTimeImmutable;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdWsprosecutor\Domain\Model\OfficeHour;

final class OfficeHourTest extends UnitTestCase
{
    public function testGetRecord(): void
    {
        $mockRecord = [
            'id' => '123',
            'startDate' => '2024-03-28T08:00:00',
            'endDate' => '2024-03-28T17:00:00',
            'modificationDate' => '2024-03-27T12:30:00',
            'evenement' => 'Hearing',
            'section' => ['libelleOfficePermanence' => 'MP Office'],
            'magistrat' => [
                'id' => 'M1',
                'prenom' => 'John',
                'nom' => 'Doe',
                'nomPrenom' => 'Doe John'
            ],
            'suppleant' => [
                'id' => 'S1',
                'prenom' => 'Jane',
                'nom' => 'Smith',
                'nomPrenom' => 'Smith Jane'
            ]
        ];

        $officeHour = new OfficeHour($mockRecord);
        $record = $officeHour->getRecord();

        // Assertions
        $this->assertEquals('123', $record['external_id']);
        $this->assertEquals('Hearing', $record['event']);
        $this->assertEquals('MP Hearing', $record['office']);
        $this->assertEquals('John', $record['magistrate_first_name']);
        $this->assertEquals('Doe', $record['magistrate_last_name']);
        $this->assertEquals('Doe John', $record['magistrate_name']);
        $this->assertEquals('Jane', $record['substitute_first_name']);
        $this->assertEquals('Smith', $record['substitute_last_name']);
        $this->assertEquals('Smith Jane', $record['substitute_name']);

        // Check timestamps
        $this->assertEquals(
            (new DateTimeImmutable('2024-03-28T08:00:00'))->getTimestamp(),
            $record['start_shift']
        );
        $this->assertEquals(
            (new DateTimeImmutable('2024-03-28T17:00:00'))->getTimestamp(),
            $record['end_shift']
        );
        $this->assertEquals(
            (new DateTimeImmutable('2024-03-27T12:30:00'))->getTimestamp(),
            $record['modification_date']
        );

        // Check formatted dates
        $this->assertEquals('28.03.2024', $record['start_shift_date']);
        $this->assertEquals('28.03.2024 08:00', $record['start_shift_date_hour']);
        $this->assertEquals('28.03.2024', $record['end_shift_date']);
        $this->assertEquals('28.03.2024 17:00', $record['end_shift_date_hour']);
    }

    public function testHandlesInvalidDateFormat(): void
    {
        $mockRecord = [
            'id' => '124',
            'startDate' => 'invalid-date',
            'endDate' => 'another-invalid-date',
            'modificationDate' => 'wrong-format'
        ];

        $officeHour = new OfficeHour($mockRecord);
        $record = $officeHour->getRecord();

        // Should fallback to null timestamps
        $this->assertIsInt($record['start_shift']);
        $this->assertIsInt($record['end_shift']);
        $this->assertIsInt($record['modification_date']);
    }
}
