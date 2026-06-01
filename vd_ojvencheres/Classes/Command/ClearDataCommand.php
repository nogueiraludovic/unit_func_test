<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Command;

use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdCore\Authentication\AuthenticationTrait;

use function count;

class ClearDataCommand extends AbstractCommand
{
    use AuthenticationTrait;

    protected array $cmd = [];

    protected function addBatchesToDelete(int $uid): ClearDataCommand
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdojvencheres_domain_model_lot');
        $queryBuilder->getRestrictions()->removeAll();

        $statement = $queryBuilder
            ->select('uid')
            ->from('tx_vdojvencheres_domain_model_lot')
            ->where(
                $queryBuilder->expr()->eq('sale', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT))
            )
            ->execute();

        while ($recordUid = $statement->fetchOne()) {
            $this->cmd['tx_vdojvencheres_domain_model_lot'][$recordUid]['delete'] = 1;
        }

        return $this;
    }

    protected function addObjectsToDelete(int $uid): ClearDataCommand
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdojvencheres_domain_model_item');
        $queryBuilder->getRestrictions()->removeAll();

        $statement = $queryBuilder
            ->select('uid')
            ->from('tx_vdojvencheres_domain_model_item')
            ->leftJoin(
                'tx_vdojvencheres_domain_model_item',
                'tx_vdojvencheres_item_sale_mm',
                'tx_vdojvencheres_item_sale_mm',
                $queryBuilder->expr()->eq(
                    'tx_vdojvencheres_item_sale_mm.uid_local',
                    $queryBuilder->quoteIdentifier('tx_vdojvencheres_domain_model_item.uid')
                )
            )
            ->where(
                $queryBuilder->expr()->eq(
                    'tx_vdojvencheres_item_sale_mm.uid_foreign',
                    $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)
                )
            )
            ->execute();

        while ($recordUid = $statement->fetchOne()) {
            $this->cmd['tx_vdojvencheres_domain_model_item'][$recordUid]['delete'] = 1;
        }

        return $this;
    }

    protected function addOrphanSaleDatesToDelete(): ClearDataCommand
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdojvencheres_domain_model_saledate');
        $queryBuilder->getRestrictions()->removeAll();

        $statement = $queryBuilder
            ->select('uid')
            ->from('tx_vdojvencheres_domain_model_saledate')
            ->where(
                $queryBuilder->expr()->eq('sale', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT))
            )
            ->execute();

        while ($recordUid = $statement->fetchOne()) {
            $this->cmd['tx_vdojvencheres_domain_model_saledate'][$recordUid['uid']]['delete'] = 1;
        }

        return $this;
    }

    protected function cleanManyToManyRelations(
        string $MMtable,
        string $tableName = 'tx_vdojvencheres_domain_model_item'
    ): ClearDataCommand {
        $queryBuilder = $this->connection->getQueryBuilderForTable($MMtable);
        $queryBuilder->getRestrictions()->removeAll();

        switch ($MMtable) {
            case 'sys_file_reference':
                $identifier = 'uid';
                $select = 'uid_foreign';
                break;
            case 'tx_vdojvencheres_lot_item_mm':
                $identifier = 'uid_foreign';
                $select = 'uid_local';
                break;
            case 'tx_vdojvencheres_item_sale_mm':
            case 'tx_vdojvencheres_sale_officeecondary_office_mm':
                $identifier = 'uid_local';
                $select = 'uid_foreign';
                break;
            default:
                return $this;
        }

        $statement = $queryBuilder
            ->select($identifier, $select)
            ->from($MMtable);

        if ($MMtable === 'sys_file_reference') {
            $statement->where($queryBuilder->expr()->eq(
                'tablenames',
                $queryBuilder->createNamedParameter($tableName)
            ));
        }

        $statement = $statement->execute();

        while ($rows = $statement->fetchAssociative()) {
            $queryBuilder = $this->connection->getQueryBuilderForTable($tableName);
            $queryBuilder->getRestrictions()->removeAll();

            $object = $queryBuilder
                ->count('*')
                ->from($tableName);

            if ($MMtable === 'sys_file_reference') {
                $object->where('uid', $queryBuilder->createNamedParameter($rows[$select], Connection::PARAM_INT));
            } else {
                $object->where('uid', $queryBuilder->createNamedParameter($rows[$identifier], Connection::PARAM_INT));
            }

            $object = $object
                ->execute()
                ->fetchOne();

            if ($object > 0) {
                continue;
            }

            $this->connection
                ->getConnectionForTable($MMtable)
                ->delete(
                    $MMtable,
                    [
                        $identifier => $rows[$identifier]
                    ]
                );
        }

        return $this;
    }

    protected function configure(): void
    {
        $this->setDescription('Clear all the data for the sales.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdojvencheres_domain_model_sale');
        $queryBuilder->getRestrictions()->removeAll();

        $statement = $queryBuilder
            ->select(
                'tx_vdojvencheres_domain_model_sale.sale_categories',
                'tx_vdojvencheres_domain_model_sale.status',
                'tx_vdojvencheres_domain_model_sale.uid',
                'tx_vdojvencheres_domain_model_saledate.sale_date'
            )
            ->from('tx_vdojvencheres_domain_model_sale')
            ->leftJoin(
                'tx_vdojvencheres_domain_model_sale',
                'tx_vdojvencheres_domain_model_saledate',
                'tx_vdojvencheres_domain_model_saledate',
                'tx_vdojvencheres_domain_model_sale.uid=tx_vdojvencheres_domain_model_saledate.sale'
            )
            ->where(
                $queryBuilder->expr()->in(
                    'tx_vdojvencheres_domain_model_sale.status',
                    $queryBuilder->createNamedParameter([1, 2, 4, 5], Connection::PARAM_INT_ARRAY)
                ),
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->isNull('tx_vdojvencheres_domain_model_saledate.sale_date'),
                    $queryBuilder->expr()->lte(
                        'tx_vdojvencheres_domain_model_saledate.sale_date',
                        $queryBuilder->createNamedParameter(
                            (new DateTime('yesterday 23:59:59'))->getTimestamp(),
                            Connection::PARAM_INT
                        )
                    )
                )
            )
            ->groupBy('tx_vdojvencheres_domain_model_sale.uid')
            ->execute();

        while ($rows = $statement->fetchAssociative()) {
            $this
                ->addBatchesToDelete($rows['uid'])
                ->addObjectsToDelete($rows['uid'])
                ->addOrphanSaleDatesToDelete();
            $this->cmd['tx_vdojvencheres_domain_model_sale'][$rows['uid']]['delete'] = 1;
        }

        $this
            ->processDataHandling($this->cmd)
            ->cleanManyToManyRelations('sys_file_reference')
            ->cleanManyToManyRelations('tx_vdojvencheres_lot_item_mm')
            ->cleanManyToManyRelations('tx_vdojvencheres_item_sale_mm')
            ->cleanManyToManyRelations(
                'tx_vdojvencheres_sale_officeecondary_office_mm',
                'tx_vdojvencheres_domain_model_sale'
            );

        return Command::SUCCESS;
    }

    protected function getClearCachePages(): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tt_content');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $statement = $queryBuilder
            ->select('pid')
            ->from('tt_content')
            ->orWhere(
                $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter('vdojvencheres_itemlist')),
                $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter('vdojvencheres_itemshow')),
                $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter('vdojvencheres_newsletter')),
                $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter('vdojvencheres_salelist')),
                $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter('vdojvencheres_saleshow'))
            )
            ->execute();

        $pages = [];

        while ($recordPid = $statement->fetchOne()) {
            $pages[] = $recordPid;
        }

        return $pages;
    }

    protected function processDataHandling(array $cmd = []): ClearDataCommand
    {
        $hooks = [];

        if (count($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']) > 0) {
            $hooks = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php'];

            unset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']);
        }

        if ($GLOBALS['LANG'] === null) {
            $GLOBALS['LANG'] = GeneralUtility::makeInstance(LanguageService::class);
        }

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], $cmd, $this->getFakeAdminUser('vd-ojvencheres'));

        if (count($cmd) > 0) {
            $dataHandler->process_cmdmap();
        }

        $pages = $this->getClearCachePages();

        if (count($pages) > 0) {
            foreach ($pages as $pageId) {
                $dataHandler->clear_cacheCmd((int)$pageId);
            }
        }

        if (count($hooks) > 0) {
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php'] = $hooks;
        }

        return $this;
    }
}
