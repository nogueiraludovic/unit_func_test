<?php

declare(strict_types=1);

namespace Vd\VdPrestations\TCA\Form;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_map;
use function array_push;
use function is_array;

class ItemRenderer
{
    protected QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_vdprestations_domain_model_prestation');
        $this->queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
            ->add(GeneralUtility::makeInstance(HiddenRestriction::class));
    }

    public function forDomains(array &$configuration): void
    {
        $domainRecords = $this->queryBuilder
            ->selectLiteral('DISTINCT `domain_id`, `domain_name`')
            ->from('tx_vdprestations_domain_model_prestation')
            ->orderBy('domain_name', 'ASC')
            ->execute()
            ->fetchAllAssociative();

        if ($domainRecords === false) {
            return;
        }

        array_push($configuration['items'], ...array_map(static function (array $entry): array {
            return [
                $entry['domain_name'],
                $entry['domain_id']
            ];
        }, $domainRecords));
    }

    public function forServices(array &$configuration): void
    {
        $prestationRecords = $this->queryBuilder
            ->select('title', 'external_id')
            ->from('tx_vdprestations_domain_model_prestation')
            ->orderBy('title', 'ASC')
            ->where(
                $this->queryBuilder->expr()->eq(
                    'domain_id',
                    $this->queryBuilder->createNamedParameter(
                        $this->resolveSetting('settings.domain', $configuration['row'])
                    )
                ),
                $this->queryBuilder->expr()->eq(
                    'theme_id',
                    $this->queryBuilder->createNamedParameter(
                        $this->resolveSetting('settings.theme', $configuration['row'])
                    )
                )
            )
            ->execute()
            ->fetchAllAssociative();

        if ($prestationRecords === false) {
            return;
        }

        array_push($configuration['items'], ...array_map(static function (array $entry): array {
            return [
                $entry['title'],
                $entry['external_id']
            ];
        }, $prestationRecords));
    }

    public function forThemes(array &$configuration): void
    {
        $settingsName = $configuration['row']['settings.domain'] !== null ? 'settings.domain' : 'services_domain';

        $themeRecords = $this->queryBuilder
            ->selectLiteral('DISTINCT `theme_id`, `theme_name`')
            ->from('tx_vdprestations_domain_model_prestation')
            ->orderBy('theme_name', 'ASC')
            ->where(
                $this->queryBuilder->expr()->eq(
                    'domain_id',
                    $this->queryBuilder->createNamedParameter(
                        $this->resolveSetting($settingsName, $configuration['row'])
                    )
                )
            )
            ->execute()
            ->fetchAllAssociative();

        if ($themeRecords === false) {
            return;
        }

        array_push($configuration['items'], ...array_map(static function (array $entry): array {
            return [
                $entry['theme_name'],
                $entry['theme_id']
            ];
        }, $themeRecords));
    }

    protected function resolveSetting(string $name, array $settings): string
    {
        if (is_array($settings[$name]) === true && $settings[$name][0] !== null) {
            return $settings[$name][0];
        }

        return (string)$settings[$name];
    }
}
