<?php

declare(strict_types=1);

namespace Vd\VdWebservice\Query;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_merge;
use function array_unique;
use function array_values;
use function getenv;
use function implode;

final readonly class BackendUsersQuery
{
    public function __construct(private ConnectionPool $connection)
    {
    }

    public function fetchAllContributors(): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('be_users');
        $queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
            ->add(GeneralUtility::makeInstance(HiddenRestriction::class));

        $statement = $queryBuilder
            ->select('email', 'realName', 'uid', 'usergroup', 'username')
            ->from('be_users')
            ->where(
                $queryBuilder->expr()->eq('admin', $queryBuilder->createNamedParameter(false, Connection::PARAM_BOOL)),
                $queryBuilder->expr()->notLike(
                    'username',
                    $queryBuilder->createNamedParameter($queryBuilder->escapeLikeWildcards('_') . '%')
                ),
                $queryBuilder->expr()->comparison(
                    $queryBuilder->quoteIdentifier('username'),
                    'NOT REGEXP',
                    $queryBuilder->createNamedParameter('qt[0-9]{5}')
                )
            )
            ->executeQuery();

        $allGroupUids = [];
        $users = [];

        while ($row = $statement->fetchAssociative()) {
            $groupUids = GeneralUtility::intExplode(',', $row['usergroup'] ?? '', true);
            /** @noinspection SlowArrayOperationsInLoopInspection */
            $allGroupUids = array_merge($allGroupUids, $groupUids);
            $users[] = [
                'email' => (string)($row['email'] ?? ''),
                'environment' => (string)(getenv('DGNSI_APP_NAME') ?: ''),
                'groupUids' => $groupUids,
                'realName' => (string)($row['realName'] ?? ''),
                'uid' => (int)($row['uid'] ?? 0),
                'username' => (string)($row['username'] ?? ''),
            ];
        }

        if ($users === []) {
            return [];
        }

        $allGroupUids = array_values(array_unique($allGroupUids));
        $groupsByUid = $this->fetchAllGroupTitles($allGroupUids);
        $records = [];

        foreach ($users as $user) {
            $groupTitles = [];

            foreach ($user['groupUids'] as $groupUid) {
                if (isset($groupsByUid[$groupUid]) === false) {
                    continue;
                }

                $groupTitles[] = $groupsByUid[$groupUid];
            }

            $records[] = [
                'email' => $user['email'],
                'environment' => $user['environment'],
                'realName' => $user['realName'],
                'uid' => $user['uid'],
                'usergroup' => implode(', ', $groupTitles),
                'username' => $user['username']
            ];
        }

        return $records;
    }

    private function fetchAllGroupTitles(array $groupUids): array
    {
        if ($groupUids === []) {
            return [];
        }

        $queryBuilder = $this->connection->getQueryBuilderForTable('be_groups');
        $queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
            ->add(GeneralUtility::makeInstance(HiddenRestriction::class));

        $statement = $queryBuilder
            ->select('title', 'uid')
            ->from('be_groups')
            ->where(
                $queryBuilder->expr()->in(
                    'uid',
                    $queryBuilder->createNamedParameter($groupUids, Connection::PARAM_INT_ARRAY)
                ),
            )
            ->executeQuery();

        $groups = [];

        while ($row = $statement->fetchAssociative()) {
            $groups[(int)$row['uid']] = (string)$row['title'];
        }

        return $groups;
    }
}
