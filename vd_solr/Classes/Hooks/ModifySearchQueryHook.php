<?php

declare(strict_types=1);

namespace Vd\VdSolr\Hooks;

use ApacheSolrForTypo3\Solr\Domain\Search\Query\Query;
use ApacheSolrForTypo3\Solr\Query\Modifier\Modifier;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function strpos;

class ModifySearchQueryHook implements Modifier
{
    public function modifyQuery(Query $query): Query
    {
        $request = $this->getRequest();
        $arguments = (array)($request->getParsedBody()['tx_solr'] ?? $request->getQueryParams()['tx_solr'] ?? []);

        if (
            isset($arguments['filter']) === false
            || $arguments['filter'][0] === null
            || strpos($arguments['filter'][0], 'category') !== 0
        ) {
            return $query;
        }

        $filterQueries = $query->getFilterQueries();

        foreach ($filterQueries as $key => $filterQuery) {
            /** @noinspection PhpUnusedLocalVariableInspection */
            [$field, $_] = GeneralUtility::trimExplode(':', $filterQuery->getOption('query'), true, 2);

            if ($field !== 'pid') {
                continue;
            }

            unset($filterQueries[$key]);
        }

        $query->setFilterQueries($filterQueries);

        return $query;
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
