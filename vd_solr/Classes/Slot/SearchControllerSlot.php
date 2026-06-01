<?php

declare(strict_types=1);

namespace Vd\VdSolr\Slot;

use DateTime;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_filter;
use function count;
use function str_replace;
use function strlen;
use function strpos;

class SearchControllerSlot
{
    public function beforeSearchSlot(array $arguments): array
    {
        $request = $this->getRequest();
        $requestArguments = (array)($request->getParsedBody()['tx_solr'] ?? $request->getQueryParams()['tx_solr']);

        $hasCreationDateRange = $requestArguments['filter'][0] !== null;

        if ($hasCreationDateRange === true && strpos($requestArguments['filter'][0], 'creationDateRange') !== 0) {
            return [$requestArguments];
        }

        $hasFromDateFilter = count(array_filter((array)$arguments['from'])) === 3;
        $hasToDateFilter = count(array_filter((array)$arguments['to'])) === 3;

        if (($hasFromDateFilter === false && $hasToDateFilter === false) && $hasCreationDateRange === false) {
            unset($arguments['from'], $arguments['to']);

            return [$arguments];
        }

        if ($hasCreationDateRange === true) {
            [$fromDate, $toDate] = GeneralUtility::trimExplode(
                '-',
                str_replace('creationDateRange:', '', $requestArguments['filter'][0]),
                true,
                2
            );

            if ($fromDate !== '199801010000') {
                $fromDate = DateTime::createFromFormat('YmdHs', $fromDate);

                $requestArguments['from'][0] = $fromDate->format('d');
                $requestArguments['from'][1] = $fromDate->format('m');
                $requestArguments['from'][2] = $fromDate->format('Y');
            }

            if ($toDate !== (new DateTime())->modify('+1 day')->format('Ymd') . '0000') {
                $toDate = DateTime::createFromFormat('YmdHs', $toDate)->modify('-1 day');

                $requestArguments['to'][0] = $toDate->format('d');
                $requestArguments['to'][1] = $toDate->format('m');
                $requestArguments['to'][2] = $toDate->format('Y');
            }

            return [$requestArguments];
        }

        if ($hasFromDateFilter === true) {
            $arguments['from'][0] = $this->resolveDayOrMonthFormat((string)((int)$arguments['from'][0]));
            $arguments['from'][1] = $this->resolveDayOrMonthFormat((string)((int)$arguments['from'][1]));

            $fromDate = $arguments['from'][2] . $arguments['from'][1] . $arguments['from'][0];
        } else {
            $fromDate = (new DateTime('1998/01/01'))->format('Ymd');
        }

        if ($hasToDateFilter === true) {
            $arguments['to'][0] = $this->resolveDayOrMonthFormat((string)((int)$arguments['to'][0]));
            $arguments['to'][1] = $this->resolveDayOrMonthFormat((string)((int)$arguments['to'][1]));

            $toDate = DateTime::createFromFormat('Ymd', $arguments['to'][2] . $arguments['to'][1] . $arguments['to'][0])
                ->modify('+1 day')
                ->format('Ymd');
        } else {
            $toDate = (new DateTime())->modify('+1 day')->format('Ymd');
        }

        $arguments['filter'][0] = 'creationDateRange:' . $fromDate . '0000-' . $toDate . '0000';

        return [$arguments];
    }

    public function manipulateValues(array $values): array
    {
        $arguments = $this->getRequest()->getQueryParams();
        $filters = (array)$arguments['tx_solr']['filter'];

        if (count($filters) === 0) {
            return [$values];
        }

        foreach ($filters as $filter) {
            [$field, $uid] = GeneralUtility::trimExplode(':', $filter, true, 2);

            if ($field === 'category') {
                break;
            }
        }

        $uid = (int)($uid ?? 0);

        $category = $this->getCategory($uid);

        if (count($category) === 0) {
            return [$values];
        }

        $category['title'] = str_replace('CdC-', '', $category['title']);

        $values['categoryData'] = $category;

        return [$values];
    }

    protected function getCategory(int $uid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $record = $queryBuilder
            ->select('*')
            ->from('sys_category')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)))
            ->execute()
            ->fetchAssociative();

        return $record ?: [];
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }

    protected function resolveDayOrMonthFormat(string $input): string
    {
        if (strlen($input) === 1) {
            return '0' . $input;
        }

        return $input;
    }
}
