<?php

declare(strict_types=1);

namespace Vd\VdSite\Command\Links;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\HttpUtility;

use function array_filter;
use function array_flip;
use function array_intersect_key;
use function count;
use function parse_str;
use function parse_url;
use function preg_match_all;
use function str_replace;
use function strpos;
use function trim;

class SanitizeBicwebLinksCommand extends AbstractLinksCommand
{
    protected function configure(): void
    {
        $this->setDescription('Sanitize "bicweb.vd.ch" links.');
    }

    protected function convertLink(string $url): string
    {
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $url = parse_url($url);

        parse_str((string)$url['query'], $query);

        if (
            ($query['pObjectID'] === null || $query['pObjectID'] === '')
            && ($query['pPage'] === null || $query['pPage'] === '')
        ) {
            return '';
        }

        $query['identifier'] =  'tx_vdpressreleases_pressrelease';

        if ((int)$query['pObjectID'] > 0) {
            $query['uid'] =  $this->fetchUidBySourceId((int)$query['pObjectID']);
        } else {
            parse_str((string)$query['pPage'], $subQuery);

            $query['uid'] =  $this->fetchUidBySourceId((int)$subQuery['/communique_aspx?pObjectID']);
        }

        if ($query['uid'] === 0) {
            return '';
        }

        $query = array_intersect_key($query, array_flip(['identifier', 'type', 'uid']));

        if ($url['fragment'] === 'AutreInfo' || $url['fragment'] === 'Detail') {
            unset($url['fragment']);
        }

        $url['host'] = 'record';
        $url['path'] = '';
        $url['query'] = HttpUtility::buildQueryString($query);
        $url['scheme'] = 't3';

        return HttpUtility::buildUrl($url);
    }

    protected function fetchUidBySourceId(int $sourceId): int
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(' tx_vdpressreleases_domain_model_pressrelease');
        $queryBuilder->getRestrictions()->removeAll();

        return (int)$queryBuilder
            ->select('uid')
            ->from('tx_vdpressreleases_domain_model_pressrelease')
            ->where(
                $queryBuilder->expr()->eq(
                    'source_id',
                    $queryBuilder->createNamedParameter($sourceId, Connection::PARAM_INT)
                )
            )
            ->execute()
            ->fetchOne();
    }

    protected function proceedRecords(): SanitizeBicwebLinksCommand
    {
        $counter = 0;

        foreach ($this->records as $table => $records) {
            foreach ($records as $record) {
                $record = array_filter($record);

                foreach ($record as $field => $value) {
                    if ($field === 'CType' || $field === 'pid' || $field === 'uid') {
                        unset($record[$field]);
                        continue;
                    }

                    if (strpos($value, 'bicweb.vd.ch') === false) {
                        unset($record[$field]);
                        continue;
                    }

                    if ($this->isInputLinkField((array)$this->processedTca[$table]['columns'][$field]) === true) {
                        $linkConfiguration = $this->codecService->decode($value);
                        $linkConfiguration['url'] = $this->convertLink($linkConfiguration['url']);

                        if ($linkConfiguration['url'] === '' || $linkConfiguration['url'] === $value) {
                            unset($record[$field]);
                            continue;
                        }

                        $url = $this->codecService->encode($linkConfiguration);

                        if ($url === $value) {
                            unset($record[$field]);
                            continue;
                        }

                        $this->logger->notice(
                            'Link ' . $value . ' converted to ' . $url . '.',
                            [
                                'CType' => $record['CType'],
                                'field' => $field,
                                'pid' => $record['pid'],
                                'table' => $table,
                                'uid' => $record['uid']
                            ]
                        );

                        $this->connectionPool
                            ->getConnectionForTable($table)
                            ->update(
                                $table,
                                [
                                    $field => $url
                                ],
                                [
                                    'uid' => $record['uid']
                                ]
                            );

                        ++$counter;
                    }

                    $isRichText = $this->isRichTextField((array)$this->processedTca[$table]['columns'][$field]);

                    if (
                        $isRichText === true
                        || $this->isUrlField($field) === true
                    ) {
                        $delimiter = '';
                        $pattern = '/https?:\/\/(?:www\.)?bicweb\.vd\.ch\/?(?:communique|frame|pdf)\.aspx?\?[\w=.?&#\/]+/';

                        if ($isRichText === true) {
                            $delimiter = '"';
                            $pattern = '/\"https?:\/\/(?:www\.)?bicweb\.vd\.ch\/?(?:communique|frame|pdf)\.aspx?\?[\w=.?&#\/]+\"/';
                        }

                        preg_match_all($pattern, $value, $matches);

                        if (count($matches[0]) === 0) {
                            unset($record[$field]);
                            continue;
                        }

                        $isSanitized = false;

                        foreach ($matches[0] as $match) {
                            $match = trim($match, '"');

                            if (strpos($match, 'bicweb.vd.ch') === false) {
                                unset($record[$field]);
                                continue;
                            }

                            $url = $this->convertLink($match);

                            if ($url === '' || $match === $url) {
                                unset($record[$field]);
                                continue;
                            }

                            $isSanitized = true;
                            $value = str_replace($delimiter . $match . $delimiter, $delimiter . $url . $delimiter, $value);

                            $this->logger->notice(
                                'Link ' . $match . ' converted to ' . $url . '.',
                                [
                                    'CType' => $record['CType'],
                                    'field' => $field,
                                    'pid' => $record['pid'],
                                    'table' => $table,
                                    'uid' => $record['uid']
                                ]
                            );
                        }

                        $this->connectionPool
                            ->getConnectionForTable($table)
                            ->update(
                                $table,
                                [
                                    $field => $value
                                ],
                                [
                                    'uid' => $record['uid']
                                ]
                            );

                        if ($isSanitized === true) {
                            ++$counter;
                        }
                    }
                }
            }
        }

        $this->addFlashMessage($counter);

        return $this;
    }

    protected function setRecordsToProceed(): SanitizeBicwebLinksCommand
    {
        foreach ($this->fields as $table => $fields) {
            $queryBuilder = $this->connectionPool->getQueryBuilderForTable($table);
            $queryBuilder->getRestrictions()->removeAll();

            $statement = $queryBuilder
                ->select(...$this->fields[$table])
                ->from($table);

            foreach ($fields as $field) {
                if ($field === 'CType' || $field === 'pid' || $field === 'uid') {
                    continue;
                }

                $statement = $statement->orWhere(
                    $queryBuilder->expr()->like(
                        $field,
                        $queryBuilder->createNamedParameter(
                            '%' . $queryBuilder->escapeLikeWildcards('bicweb.vd.ch') . '%'
                        )
                    )
                );
            }

            $statement = $statement->execute();

            while ($records = $statement->fetchAllAssociative()) {
                $this->records[$table] = $records;
            }
        }

        return $this;
    }
}
