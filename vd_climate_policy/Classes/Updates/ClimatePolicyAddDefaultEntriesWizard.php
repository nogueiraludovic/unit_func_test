<?php

declare(strict_types=1);

namespace Vd\VdClimatePolicy\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class ClimatePolicyAddDefaultEntriesWizard implements UpgradeWizardInterface
{
    protected ConnectionPool $connectionPool;
    protected const AXIS = [
        'Participation',
        'Planification',
        'Réalisation',
        'Réglementation',
        'Sensibilisation'
    ];
    protected const DICASTERIES = [
        'Bâtiments',
        'Education',
        'Infrastructures',
        'Population',
        'Ressources humaines',
        'Transport et Mobilité',
        'Voirie et Espaces verts'
    ];
    protected const PECC_NUMBERS = [
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '10',
        '11',
        '12',
        '13',
        '14',
        '15',
        '16',
        '16b',
        '16c',
        '17',
        '18',
        '19',
        '20',
        '21',
        '22',
        '23'
    ];
    protected const STORAGE_PID = 2025716;
    protected const THEMES = [
        'Alimentation',
        'Biodiversité',
        'Construction durable',
        'Déchets',
        'Démarches transversales',
        'Energie',
        'Manifestations',
        'Mobilité',
        'Santé'
    ];

    public function __construct()
    {
        $this->connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
    }

    public function executeUpdate(): bool
    {
        $this->insertNames('tx_vdclimatepolicy_domain_model_axis', self::AXIS);
        $this->insertNames('tx_vdclimatepolicy_domain_model_theme', self::THEMES);
        $this->insertNames('tx_vdclimatepolicy_domain_model_dicastery', self::DICASTERIES);
        $this->insertPecc();

        return true;
    }

    public function getDescription(): string
    {
        return 'Adds initial Axe, Thème, Dicastère and Fiche PECC records to the climate policy tables.';
    }

    public function getIdentifier(): string
    {
        return 'vdClimatePolicyAddDefaultEntriesWizard';
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    public function getTitle(): string
    {
        return 'vd_climate_policy: Add default values inside join tables';
    }

    public function updateNecessary(): bool
    {
        return $this->containsMissingName('tx_vdclimatepolicy_domain_model_axis', self::AXIS)
            || $this->containsMissingName('tx_vdclimatepolicy_domain_model_theme', self::THEMES)
            || $this->containsMissingName('tx_vdclimatepolicy_domain_model_dicastery', self::DICASTERIES)
            || $this->containsMissingPecc();
    }

    private function insertNames(string $table, array $names): void
    {
        foreach ($names as $name) {
            if ($this->recordExistsByName($table, $name) === true) {
                continue;
            }

            $this->connectionPool->getConnectionForTable($table)->insert(
                $table,
                [
                    'pid' => self::STORAGE_PID,
                    'name' => $name
                ]
            );
        }
    }

    private function containsMissingName(string $table, array $names): bool
    {
        foreach ($names as $name) {
            if ($this->recordExistsByName($table, $name) === false) {
                return true;
            }
        }

        return false;
    }

    private function recordExistsByName(string $table, string $name): bool
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable($table);

        $count = $queryBuilder
            ->count('uid')
            ->from($table)
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter(self::STORAGE_PID, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'name',
                    $queryBuilder->createNamedParameter($name)
                )
            )
            ->execute()
            ->fetchOne();

        return (int)$count > 0;
    }

    private function insertPecc(): void
    {
        foreach (self::PECC_NUMBERS as $number) {
            if ($this->recordExistsByNumber('tx_vdclimatepolicy_domain_model_pecc', $number) === true) {
                continue;
            }

            $this->connectionPool->getConnectionForTable('tx_vdclimatepolicy_domain_model_pecc')->insert(
                'tx_vdclimatepolicy_domain_model_pecc',
                [
                    'pid' => self::STORAGE_PID,
                    'number' => $number,
                    'name' => $number === '13'
                        ? 'Planifier l\'approvisionnement en énergie du territoire communal'
                        : 'Fiche PECC ' . $number,
                    'color' => '#004a99'
                ]
            );
        }
    }

    private function containsMissingPecc(): bool
    {
        foreach (self::PECC_NUMBERS as $number) {
            if ($this->recordExistsByNumber('tx_vdclimatepolicy_domain_model_pecc', $number) === false) {
                return true;
            }
        }

        return false;
    }

    private function recordExistsByNumber(string $table, string $number): bool
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable($table);

        $count = $queryBuilder
            ->count('uid')
            ->from($table)
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter(self::STORAGE_PID, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'number',
                    $queryBuilder->createNamedParameter($number)
                )
            )
            ->execute()
            ->fetchOne();

        return (int)$count > 0;
    }
}
