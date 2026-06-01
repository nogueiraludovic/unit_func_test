<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Hooks;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function strpos;

class DataHandlerHook
{
    protected ConnectionPool $connection;

    public function __construct(ConnectionPool $connection)
    {
        $this->connection = $connection;
    }

    public function processDatamap_afterDatabaseOperations($status, $table, $id, $fields, $dataHandler): void
    {
        if ($table !== 'tx_vdojvencheres_domain_model_item') {
            return;
        }

        $uid = (int)($status === 'new' ? $dataHandler->substNEWwithIDs[$id] : $id);

        $this->generateSlug($table, $uid, $fields);
    }

    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fields): void
    {
        switch ($table) {
            case 'tx_vdojvencheres_domain_model_item':
                $conditionsUid = (int)($fields['item_conditions'] ?? 0);

                if ($status === 'new') {
                    return;
                }

                $queryBuilder = $this->connection->getQueryBuilderForTable($table);
                $queryBuilder->getRestrictions()->removeAll();

                $itemConditions = $queryBuilder
                    ->select('item_conditions')
                    ->from($table)
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($id, Connection::PARAM_INT)
                        )
                    )
                    ->execute()
                    ->fetchOne();

                if ($itemConditions === false || (int)$itemConditions === $conditionsUid) {
                    return;
                }

                $queryBuilder = $this->connection
                    ->getQueryBuilderForTable('tx_vdojvencheres_domain_model_itemcondition');
                $queryBuilder->getRestrictions()->removeAll();

                $description = $queryBuilder
                    ->select('description')
                    ->from('tx_vdojvencheres_domain_model_itemcondition')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($conditionsUid, Connection::PARAM_INT)
                        )
                    )
                    ->execute()
                    ->fetchOne();

                if ($description === false) {
                    return;
                }

                $fields['conditions'] = $description;
                break;
            case 'tx_vdojvencheres_domain_model_sale':
                $tsConfig = BackendUtility::getTCEFORM_TSconfig($table, ['uid' => $id]);
                $officeUid = isset($tsConfig['secondary_office']['PAGE_TSCONFIG_ID']) === true
                    ? (int)($tsConfig['secondary_office']['PAGE_TSCONFIG_ID'] ?? 0)
                    : 0;

                $fields['main_office'] = $officeUid;

                if (
                    $status !== 'new'
                    || isset($fields['sale_categories']) === false
                    || (int)($fields['sale_categories'] ?? 0) !== 1
                ) {
                    return;
                }

                $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdojvencheres_domain_model_office');
                $queryBuilder->getRestrictions()->removeAll();

                $office = $queryBuilder
                    ->select('address', 'city', 'postal_code', 'zip_code')
                    ->from('tx_vdojvencheres_domain_model_office')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($officeUid, Connection::PARAM_INT)
                        )
                    )
                    ->execute()
                    ->fetchAssociative();

                if ($office === false) {
                    return;
                }

                $fields['address'] = $office['address'];
                $fields['city'] = $office['city'];
                $fields['po_box'] = $office['postal_code'];
                $fields['zip_code'] = $office['zip_code'];
                break;
        }
    }

    protected function generateSlug(string $table, int $uid, array $fields): void
    {
        if (isset($fields['name']) === false) {
            return;
        }

        $record = BackendUtility::getRecord($table, $uid, 'name,path_segment,pid');

        if (strpos((string)($record['path_segment'] ?? ''), 'default-') !== 0) {
            return;
        }

        $slugHelper = GeneralUtility::makeInstance(
            SlugHelper::class,
            $table,
            'path_segment',
            $GLOBALS['TCA'][$table]['columns']['path_segment']['config']
        );

        $this->connection
            ->getConnectionForTable($table)
            ->update(
                $table,
                [
                    'path_segment' => $slugHelper->generate($record, $record['pid'])
                ],
                [
                    'uid' => $uid
                ]
            );
    }
}
