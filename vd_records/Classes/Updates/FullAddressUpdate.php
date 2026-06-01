<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdRecords\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;
use Vd\VdRecords\Service\AddressService;

final class FullAddressUpdate implements UpgradeWizardInterface
{
    private AddressService $addressService;
    private ConnectionPool $connection;

    public function __construct(AddressService $addressService, ConnectionPool $connection)
    {
        $this->addressService = $addressService;
        $this->connection = $connection;
    }

    public function executeUpdate(): bool
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tt_address');
        $queryBuilder->getRestrictions()->removeAll();

        $statement = $queryBuilder
            ->select('address', 'city', 'country', 'uid', 'zip')
            ->from('tt_address')
            ->execute();

        while ($record = $statement->fetchAssociative()) {
            $this->connection
                ->getConnectionForTable('tt_address')
                ->update(
                    'tt_address',
                    [
                        'full_address' => $this->addressService->getOneLineAddress($record)
                    ],
                    [
                        'uid' => $record['uid']
                    ]
                );
        }

        return true;
    }

    public function getDescription(): string
    {
        return 'Set the field "full_address" for all "tt_address" records.';
    }

    public function getIdentifier(): string
    {
        return 'vdRecordsFullAddressUpdate';
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    public function getTitle(): string
    {
        return 'EXT:vd_records Add full address for "tt_address"';
    }

    public function updateNecessary(): bool
    {
        return true;
    }
}
