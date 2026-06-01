<?php

declare(strict_types=1);

namespace Vd\VdDirectory\XClasses\Database;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdFrontend\Database\Query\Restriction\StoragePidsRestriction;
use Vd\VdFrontend\Database\RecordRepository;
use Vd\VdFrontend\DataTransferObject\DemandInterface;
use Vd\VdFrontend\Utility\FlexFormUtility;

use function count;
use function method_exists;

class RecordRepositoryXClass extends RecordRepository
{
    public function fetchAllForFormOptions(
        DemandInterface $demand,
        string $field,
        string $table,
        bool $respectStorage = true,
        int $storagePid = 0
    ): array {
        if (
            $table !== 'tx_vddirectory_domain_model_sector'
            && $table !== 'tx_vddirectory_domain_model_service'
            && $table !== 'tx_vddirectory_domain_model_theme'
        ) {
            return parent::fetchAllForFormOptions($demand, $field, $table, $respectStorage, $storagePid);
        }

        $contentUid = $demand->getContentUid();

        if ($contentUid === 0) {
            return parent::fetchAllForFormOptions($demand, $field, $table, $respectStorage, $storagePid);
        }

        switch ($table) {
            case 'tx_vddirectory_domain_model_sector':
                $field = 'sector';
                break;
            case 'tx_vddirectory_domain_model_service':
                $field = 'service';
                break;
            case 'tx_vddirectory_domain_model_theme':
                $field = 'theme';
                break;
            default:
                $field = '';
        }

        if ($field === '') {
            return parent::fetchAllForFormOptions($demand, $field, $table, $respectStorage, $storagePid);
        }

        $this->queryBuilder = $this->connection->getQueryBuilderForTable($table);
        $this->queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        if ($respectStorage === true) {
            $this->queryBuilder
                ->getRestrictions()
                ->add(
                    GeneralUtility::makeInstance(
                        StoragePidsRestriction::class,
                        ($storagePid > 0 ? (array)$storagePid : $this->settingService->getStoragePids())
                    )
                );
        }

        $this->queryBuilder
            ->selectLiteral(
                'DISTINCT `tx_vddirectory_domain_model_address`.`' . $field . '`',
                '`' . $table . '`.`name` AS title',
                '`' . $table . '`.`uid`'
            )
            ->from($table)
            ->join(
                $table,
                'tx_vddirectory_domain_model_address',
                'tx_vddirectory_domain_model_address',
                $this->queryBuilder->expr()->eq(
                    $table . '.uid',
                    $this->queryBuilder->quoteIdentifier('tx_vddirectory_domain_model_address.' . $field)
                )
            );

        $search = $demand->getSearch();
        $sectorUId = (int)($search[FlexFormUtility::getFieldName('sector', $contentUid)] ?? 0);

        if ($sectorUId > 0) {
            $this->queryBuilder->andWhere(
                $this->queryBuilder->expr()->eq(
                    'tx_vddirectory_domain_model_address.sector',
                    $this->queryBuilder->createNamedParameter($sectorUId, Connection::PARAM_INT)
                )
            );
        }

        $serviceUid = (int)($search[FlexFormUtility::getFieldName('service', $contentUid)] ?? 0);

        if ($serviceUid > 0) {
            $this->queryBuilder->andWhere(
                $this->queryBuilder->expr()->eq(
                    'tx_vddirectory_domain_model_address.service',
                    $this->queryBuilder->createNamedParameter($serviceUid, Connection::PARAM_INT)
                )
            );
        }

        $themeUId = (int)($search[FlexFormUtility::getFieldName('theme', $contentUid)] ?? 0);

        if ($themeUId > 0) {
            $this->queryBuilder->andWhere(
                $this->queryBuilder->expr()->eq(
                    'tx_vddirectory_domain_model_address.theme',
                    $this->queryBuilder->createNamedParameter($themeUId, Connection::PARAM_INT)
                )
            );
        }

        $constraints = [];

        foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][self::class]['modifyQuery'] ?? [] as $className) {
            $hookObject = GeneralUtility::makeInstance($className);

            if (method_exists($hookObject, 'modifyQuery') === true) {
                $constraints = $hookObject->modifyQuery($demand, $table, $this->queryBuilder);
            }
        }

        if (count($constraints) > 0) {
            $this->queryBuilder->andWhere(...$constraints);
        }

        $statement = $this->queryBuilder
            ->orderBy($table . '.name')
            ->execute();

        while ($rows = $statement->fetchAssociative()) {
            $records[] = [
                'title' => $rows['title'],
                'uid' => $rows['uid']
            ];
        }

        return $records ?? [];
    }
}
