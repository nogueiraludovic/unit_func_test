<?php

declare(strict_types=1);

namespace Vd\VdDirectory\Database;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Vd\VdFrontend\Database\Query\Restriction\StoragePidsRestriction;
use Vd\VdFrontend\Utility\FlexFormUtility;

class RecordRepository
{
    protected ConnectionPool $connection;

    public function __construct()
    {
        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class);
    }

    public function fetchSectorColors(): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vddirectory_domain_model_sector');

        $statement = $queryBuilder
            ->select('color', 'uid')
            ->from('tx_vddirectory_domain_model_sector')
            ->execute();

        while ($rows = $statement->fetchAssociative()) {
            $records[] = $rows;
        }

        return $records ?? [];
    }

    public function fetchSelectItemsByParentUids(array $arguments): array
    {
        if (isset($arguments['table']) === false) {
            return [];
        }

        switch ($arguments['table']) {
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
            return [];
        }

        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vddirectory_domain_model_address');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $contentUid = (int)($arguments['contentUid'] ?? 0);

        $content = BackendUtility::getRecord('tt_content', $contentUid, 'list_type, pi_flexform');

        if ($content['list_type'] !== 'vdfrontend_recordlist') {
            return [];
        }

        $storagePids = FlexFormUtility::getStoragePids($content['pi_flexform']);

        if ($storagePids !== []) {
            $queryBuilder
                ->getRestrictions()
                ->add(
                    GeneralUtility::makeInstance(
                        StoragePidsRestriction::class,
                        $storagePids
                    )
                );
        }

        $statement = $queryBuilder
            ->selectLiteral(
                'DISTINCT `tx_vddirectory_domain_model_address`.`' . $field . '`',
                '`' . $arguments['table'] . '`.`name`',
                '`' . $arguments['table'] . '`.`uid`'
            )
            ->from('tx_vddirectory_domain_model_address')
            ->join(
                'tx_vddirectory_domain_model_address',
                $arguments['table'],
                $arguments['table'],
                $queryBuilder->expr()->eq(
                    'tx_vddirectory_domain_model_address.' . $field,
                    $queryBuilder->quoteIdentifier($arguments['table'] . '.uid')
                )
            );

        if (isset($arguments['sector']) === true) {
            $statement = $statement->andWhere(
                $queryBuilder->expr()->eq(
                    'tx_vddirectory_domain_model_address.sector',
                    $queryBuilder->createNamedParameter($arguments['sector'], Connection::PARAM_INT)
                )
            );
        }

        if (isset($arguments['service']) === true) {
            $statement = $statement->andWhere(
                $queryBuilder->expr()->eq(
                    'tx_vddirectory_domain_model_address.service',
                    $queryBuilder->createNamedParameter($arguments['service'], Connection::PARAM_INT)
                )
            );
        }

        if (isset($arguments['theme']) === true) {
            $statement = $statement->andWhere(
                $queryBuilder->expr()->eq(
                    'tx_vddirectory_domain_model_address.theme',
                    $queryBuilder->createNamedParameter($arguments['theme'], Connection::PARAM_INT)
                )
            );
        }

        $statement = $statement
            ->orderBy($arguments['table'] . '.name')
            ->execute();

        $records[] = [
            'name' => LocalizationUtility::translate('all', 'VdFrontend', null, 'fr'),
            'uid' => ''
        ];

        while ($rows = $statement->fetchAssociative()) {
            $records[] = [
                'name' => $rows['name'],
                'uid' => $rows['uid']
            ];
        }

        return $records ?? [];
    }
}
