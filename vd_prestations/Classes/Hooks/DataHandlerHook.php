<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Hooks;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

use function count;

class DataHandlerHook
{
    public function processDatamap_afterDatabaseOperations(string $status, string $table, string $uid): void
    {
        if ($status !== 'update' || $table !== 'tt_content') {
            return;
        }

        $record = BackendUtility::getRecord('tt_content', (int)$uid, 'CType,list_type,pi_flexform,pid');

        if ($record['CType'] !== 'list' || $record['list_type'] !== 'vdprestations_pi1') {
            return;
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_vdprestations_domain_model_prestation');
        $flexForm = GeneralUtility::makeInstance(FlexFormService::class)
            ->convertFlexFormContentToArray($record['pi_flexform']);

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();

        $prestations = $queryBuilder
            ->select('external_id')
            ->from('tx_vdprestations_domain_model_prestation')
            ->where(
                $queryBuilder->expr()->eq(
                    'domain_id',
                    $queryBuilder->createNamedParameter($flexForm['settings']['domain'])
                ),
                $queryBuilder->expr()->eq(
                    'theme_id',
                    $queryBuilder->createNamedParameter($flexForm['settings']['theme'])
                )
            )
            ->execute()
            ->fetchAllAssociative();

        if (count($prestations) === 0) {
            return;
        }

        $pages = GeneralUtility::makeInstance(RootlineUtility::class, $record['pid'])->get();

        foreach ($pages as $key => $page) {
            if ($page['uid'] !== 1002722) {
                continue;
            }

            $domain = $pages[$key + 1]['uid'];
            $theme = $pages[$key + 2]['uid'];
            break;
        }

        foreach ($prestations as $prestation) {
            $connection->update(
                'tx_vdprestations_domain_model_prestation',
                [
                    'domain_target_page' => $domain ?? 0,
                    'theme_target_page' => $theme ?? 0
                ],
                [
                    'external_id' =>  $prestation['external_id']
                ]
            );
        }
    }
}
