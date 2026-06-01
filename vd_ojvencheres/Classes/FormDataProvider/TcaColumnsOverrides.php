<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\FormDataProvider;

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Localization\LanguageService;

use function str_replace;

class TcaColumnsOverrides implements FormDataProviderInterface
{
    protected ConnectionPool $connection;
    protected LanguageService $languageService;

    public function __construct(ConnectionPool $connection)
    {
        $this->connection = $connection;
        $this->languageService = $this->getLanguageService();
    }

    public function addData(array $result): array
    {
        switch ($result['tableName']) {
            case 'tx_vdojvencheres_domain_model_item':
                $this->addDataForItem($result);
                break;
            case 'tx_vdojvencheres_domain_model_lot':
                $this->addDataForLot($result);
                break;
            case 'tx_vdojvencheres_domain_model_sale':
                $this->addDataForSale($result);
                break;
            case 'tx_vdojvencheres_domain_model_saledate':
                $this->addDataForSaleDate($result);
                break;
        }

        return $result;
    }

    protected function addDataForItem(array &$result): void
    {
        $databaseRow = (array)($result['databaseRow'] ?? []);

        if ($databaseRow === []) {
            return;
        }

        $itemUid = (int)($databaseRow['uid'] ?? 0);

        if ($itemUid === 0) {
            return;
        }

        $selectedCategoryUid = (int)($databaseRow['item_categories'][0] ?? 0);

        if ($selectedCategoryUid === 0) {
            return;
        }

        $tsConfig = BackendUtility::getTCEFORM_TSconfig('tx_vdojvencheres_domain_model_sale', $databaseRow);
        $officeUid = (int)($tsConfig['secondary_office']['PAGE_TSCONFIG_ID'] ?? 0);

        $categories = [];
        $sales = [];

        if ($officeUid > 0) {
            $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdojvencheres_domain_model_itemcategory');
            $queryBuilder->getRestrictions()->removeAll();

            $statement = $queryBuilder
                ->select('name', 'uid')
                ->from('tx_vdojvencheres_domain_model_itemcategory')
                ->where(
                    $queryBuilder->expr()->eq(
                        'parent',
                        $queryBuilder->createNamedParameter($selectedCategoryUid, Connection::PARAM_INT)
                    )
                )
                ->execute();

            $categories[] = [
                '',
                ''
            ];

            while ($rows = $statement->fetchAssociative()) {
                $categories[] = [
                    $rows['name'],
                    $rows['uid'],
                    'mimetypes-x-item-category'
                ];
            }

            $sales = $this->fetchSalesByOffice($officeUid);
        }

        $result['processedTca']['columns']['item_sub_categories']['config'] = [
            'default' => '',
            'disableNoMatchingValueElement' => true,
            'eval' => 'required',
            'items' => $categories,
            'minitems' => 1,
            'renderType' => 'selectSingle',
            'type' => 'select'
        ];
        $result['processedTca']['columns']['price']['config']['eval'] = 'double2';
        $result['processedTca']['columns']['sale']['config']['items'] = $sales;

        $result['processedTca']['types'][0]['showitem'] = str_replace(
            'conditions,',
            '',
            $result['processedTca']['types'][0]['showitem']
        );

        switch ($selectedCategoryUid) {
            case 2:
            case 5:
            case 6:
                $result['processedTca']['types'][0]['showitem'] = '
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                        item_categories,
                        name,
                        path_segment,
                        sale,
                        item_sub_categories,
                        price,
                        description,
                        item_conditions,
                        conditions,
                        documents,
                        pictures,
                        observations,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                        hidden,
                        --palette--;;timeRestriction
                ';
                break;
            case 3:
                $result['processedTca']['columns']['charge_state']['config']['minitems'] = 1;
                $result['processedTca']['columns']['documents']['label'] =
                    $this->languageService->sL(
                        'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:other_documents'
                    );
                $result['processedTca']['columns']['price']['config']['eval'] = 'double2,required';
                $result['processedTca']['columns']['price']['config']['default'] = '20';
                $result['processedTca']['columns']['price']['label'] =
                    $this->languageService->sL(
                        'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:estimation'
                    );
                $result['processedTca']['columns']['sale_conditions_file']['config']['minitems'] = 1;
                $result['processedTca']['columns']['surface']['config']['eval'] = 'trim';

                $result['processedTca']['types'][0]['showitem'] = '
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                        item_categories,
                        name,
                        path_segment,
                        sale,
                        item_sub_categories,
                        price,
                        --palette--;;address,
                        --palette--;;property,
                        description,
                        sale_conditions_file,
                        charge_state,
                        expertise_report,
                        documents,
                        pictures,
                        observations,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                        hidden,
                        --palette--;;timeRestriction
                    ';
                break;
            case 4:
                $result['processedTca']['columns']['description']['config']['eval'] = 'trim';
                $result['processedTca']['columns']['description']['label'] =
                    $this->languageService->sL(
                        'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:complement'
                    );
                $result['processedTca']['columns']['year']['label'] =
                    $this->languageService->sL(
                        'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:circulation_year'
                    );

                $result['processedTca']['types'][0]['showitem'] = '
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                        item_categories,
                        name,
                        path_segment,
                        sale,
                        item_sub_categories,
                        price,
                        year,
                        --palette--;;address,
                        --palette--;;vehicle,
                        description,
                        item_conditions,
                        conditions,
                        documents,
                        pictures,
                        observations,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                        hidden,
                        --palette--;;timeRestriction
                    ';
                break;
        }
    }

    protected function addDataForLot(array &$result): void
    {
        $databaseRow = (array)($result['databaseRow'] ?? []);

        if ($databaseRow === []) {
            return;
        }

        $tsConfig = BackendUtility::getTCEFORM_TSconfig('tx_vdojvencheres_domain_model_sale', $databaseRow);
        $officeUid = (int)($tsConfig['secondary_office']['PAGE_TSCONFIG_ID'] ?? 0);

        $sales = [];

        if ($officeUid > 0) {
            $sales = $this->fetchSalesByOffice($officeUid);
        }

        $result['processedTca']['columns']['sale']['config']['items'] = $sales;

        $saleUid = (int)($databaseRow['sale'][0] ?? 0);

        if ($saleUid === 0) {
            return;
        }

        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdojvencheres_domain_model_item');
        $queryBuilder->getRestrictions()->removeAll();

        $statement = $queryBuilder
            ->select('tx_vdojvencheres_domain_model_item.name', 'tx_vdojvencheres_domain_model_item.uid')
            ->from('tx_vdojvencheres_domain_model_item')
            ->innerJoin(
                'tx_vdojvencheres_domain_model_item',
                'tx_vdojvencheres_item_sale_mm',
                'tx_vdojvencheres_item_sale_mm',
                'tx_vdojvencheres_domain_model_item.canceled=0 AND tx_vdojvencheres_domain_model_item.deleted=0 AND tx_vdojvencheres_domain_model_item.uid=tx_vdojvencheres_item_sale_mm.uid_local'
            )
            ->innerJoin(
                'tx_vdojvencheres_item_sale_mm',
                'tx_vdojvencheres_domain_model_sale',
                'tx_vdojvencheres_domain_model_sale',
                'tx_vdojvencheres_domain_model_sale.deleted=0 AND tx_vdojvencheres_domain_model_sale.uid=' . $saleUid . ' AND tx_vdojvencheres_item_sale_mm.uid_foreign=tx_vdojvencheres_domain_model_sale.uid'
            )
            ->groupBy('tx_vdojvencheres_domain_model_item.uid')
            ->orderBy('tx_vdojvencheres_domain_model_item.name')
            ->execute();

        $items = [];

        while ($rows = $statement->fetchAssociative()) {
            $items[] = [
                $rows['name'],
                $rows['uid'],
                'mimetypes-x-item'
            ];
        }

        $result['processedTca']['columns']['items']['config'] = [
            'default' => 0,
            'items' => $items,
            'maxitems' => 9999,
            'MM' => 'tx_vdojvencheres_lot_item_mm',
            'renderType' => 'selectMultipleSideBySide',
            'type' => 'select'
        ];
    }

    protected function addDataForSale(array &$result): void
    {
        $GLOBALS['TCA']['tx_vdojvencheres_domain_model_saledate']['types'][0]['showitem'] = str_replace(
            'hour,',
            '',
            $GLOBALS['TCA']['tx_vdojvencheres_domain_model_saledate']['types'][0]['showitem']
        );

        $databaseRow = (array)($result['databaseRow'] ?? []);

        if ($databaseRow === []) {
            return;
        }

        $saleCategory = (int)($databaseRow['sale_categories'][0] ?? 0);

        if ($saleCategory > 0) {
            $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdojvencheres_domain_model_salecondition');
            $queryBuilder->getRestrictions()->removeAll();

            $statement = $queryBuilder
                ->select(
                    'tx_vdojvencheres_domain_model_salecondition.name',
                    'tx_vdojvencheres_domain_model_salecondition.uid'
                )
                ->from('tx_vdojvencheres_domain_model_salecondition')
                ->innerJoin(
                    'tx_vdojvencheres_domain_model_salecondition',
                    'tx_vdojvencheres_salecondition_salecategory_mm',
                    'tx_vdojvencheres_salecondition_salecategory_mm',
                    'tx_vdojvencheres_domain_model_salecondition.deleted=0 AND tx_vdojvencheres_domain_model_salecondition.uid=tx_vdojvencheres_salecondition_salecategory_mm.uid_local'
                )
                ->innerJoin(
                    'tx_vdojvencheres_salecondition_salecategory_mm',
                    'tx_vdojvencheres_domain_model_salecategory',
                    'tx_vdojvencheres_domain_model_salecategory',
                    'tx_vdojvencheres_domain_model_salecategory.deleted=0 AND tx_vdojvencheres_domain_model_salecategory.uid=' . $saleCategory . ' AND tx_vdojvencheres_salecondition_salecategory_mm.uid_foreign=tx_vdojvencheres_domain_model_salecategory.uid'
                )
                ->groupBy('tx_vdojvencheres_domain_model_salecondition.uid')
                ->orderBy('tx_vdojvencheres_domain_model_salecondition.uid')
                ->execute();

            $conditions = [];

            while ($rows = $statement->fetchAssociative()) {
                $conditions[] = [
                    $rows['name'],
                    $rows['uid'],
                    'mimetypes-x-sale-condition'
                ];
            }

            $result['processedTca']['columns']['sale_conditions']['config']['items'] = $conditions;
        }

        if ($saleCategory === 1) {
            $result['processedTca']['columns']['address']['label'] =
                $this->languageService->sL(
                    'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:reply_address'
                );
            $result['processedTca']['columns']['exposure']['config']['eval'] = 'trim,required';
            $result['processedTca']['columns']['sale_date']['label'] =
                $this->languageService->sL(
                    'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:submission_deadline'
                );
        }

        if ($saleCategory === 2) {
            $result['processedTca']['columns']['sale_date']['config']['maxitems'] = 9999;
        }

        $status = (int)($databaseRow['status'][0] ?? 0);

        if ((int)($databaseRow['pub_date'] ?? 0) > 0 && $status === 1) {
            $result['processedTca']['columns']['pub_date']['config']['readOnly'] = true;
            $result['processedTca']['columns']['pub_date']['label'] =
                $this->languageService->sL(
                    'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:effective_publication_date'
                );
        }

        switch ($status) {
            case 4:
                unset(
                    $result['processedTca']['columns']['status']['config']['items'][1],
                    $result['processedTca']['columns']['status']['config']['items'][2]
                );
                break;
            case 5:
                unset(
                    $result['processedTca']['columns']['status']['config']['items'][1],
                    $result['processedTca']['columns']['status']['config']['items'][2],
                    $result['processedTca']['columns']['status']['config']['items'][3]
                );
                break;
            default:
                unset(
                    $result['processedTca']['columns']['status']['config']['items'][0],
                    $result['processedTca']['columns']['status']['config']['items'][3],
                    $result['processedTca']['columns']['status']['config']['items'][4]
                );
        }
    }

    protected function addDataForSaleDate(array &$result): void
    {
        $saleUid = $result['databaseRow']['sale'][0];

        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdojvencheres_domain_model_sale');
        $queryBuilder->getRestrictions()->removeAll();

        $category = (int)$queryBuilder
            ->select('sale_categories')
            ->from('tx_vdojvencheres_domain_model_sale')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($saleUid, Connection::PARAM_INT))
            )
            ->execute()
            ->fetchOne();

        if ($category === 1) {
            $result['processedTca']['columns']['sale_date']['label'] =
                $this->languageService->sL(
                    'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang_db.xlf:submission_deadline'
                );

            $result['processedTca']['types'][0]['showitem'] = str_replace(
                'hour,',
                '',
                $result['processedTca']['types'][0]['showitem']
            );
        }
    }

    protected function fetchSalesByOffice(int $officeUid): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdojvencheres_domain_model_sale');
        $queryBuilder->getRestrictions()->removeAll();

        $statement = $queryBuilder
            ->select('tx_vdojvencheres_domain_model_sale.name', 'tx_vdojvencheres_domain_model_sale.uid')
            ->from('tx_vdojvencheres_domain_model_sale')
            ->innerJoin(
                'tx_vdojvencheres_domain_model_sale',
                'tx_vdojvencheres_domain_model_office',
                'tx_vdojvencheres_domain_model_office',
                'tx_vdojvencheres_domain_model_office.uid=' . $officeUid . ' AND tx_vdojvencheres_domain_model_sale.deleted=0 AND tx_vdojvencheres_domain_model_sale.main_office=tx_vdojvencheres_domain_model_office.uid'
            )
            ->where(
                $queryBuilder->expr()->in(
                    'tx_vdojvencheres_domain_model_sale.status',
                    $queryBuilder->createNamedParameter([0, 1], Connection::PARAM_INT_ARRAY)
                )
            )
            ->execute();

        $sales[] = [
            '',
            ''
        ];

        while ($rows = $statement->fetchAssociative()) {
            $sales[] = [
                $rows['name'],
                $rows['uid'],
                'mimetypes-x-sale'
            ];
        }

        return $sales;
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
