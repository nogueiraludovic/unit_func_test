<?php

declare(strict_types=1);

namespace Vd\VdSite\Command\Links;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Frontend\Page\CacheHashCalculator;

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

class SanitizeVdLinksCommand extends AbstractLinksCommand
{
    protected CacheHashCalculator $cacheHashCalculator;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->cacheHashCalculator = GeneralUtility::makeInstance(CacheHashCalculator::class);
    }

    protected function configure(): void
    {
        $this->setDescription('Sanitize "vd.ch/index.php?id=" links.');
    }

    protected function convertNormalizedLink(string $url): string
    {
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $url = parse_url($url);

        parse_str((string)$url['query'], $query);

        $url['host'] = 'page';
        $url['path'] = '';
        $url['scheme'] = 't3';

        $query['uid'] = $query['id'];

        if ($query['tx_powermail_pi1'] !== null) {
            $query['identifier'] = 'tx_vdcontactservice_domain_model_service';
            $query['uid'] = $query['tx_powermail_pi1']['uid'];

            $url['host'] = 'record';
        } elseif ($query['tx_vdnews_pi1'] !== null) {
            if ($query['tx_vdnews_pi1']['category'] !== null) {
                $query['identifier'] = 'news_category';
                $query['uid'] = $query['tx_vdnews_pi1']['category'];
            } else {
                $query['identifier'] = 'news';
                $query['uid'] = $query['tx_vdnews_pi1']['news'];
            }

            $url['host'] = 'record';
        } elseif ($query['tx_vdpressreleases_pressrelease'] !== null) {
            $query['identifier'] = 'tx_vdpressreleases_pressrelease';
            $query['uid'] = $query['tx_vdpressreleases_pressrelease']['pressRelease'];

            $url['host'] = 'record';
        } elseif ($query['tx_vdprestations_pi4'] !== null) {
            $query['identifier'] = 'tx_vdprestations';
            $query['uid'] = $query['tx_vdprestations_pi4']['prestation'];

            $url['host'] = 'record';
        }

        $query = array_intersect_key($query, array_flip(['identifier', 'type', 'uid']));

        $url['query'] = HttpUtility::buildQueryString($query);

        return HttpUtility::buildUrl($url);
    }

    protected function normalizeLink(string $url): string
    {
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $url = parse_url($url);

        parse_str((string)$url['query'], $query);

        if ($query['id'] === null || $query['id'] === '') {
            return '';
        }

        $query['id'] = $query['id'] < 1000000 ? $query['id'] += 1000000 : $query['id'];

        unset($query['amp'], $query['no_cache']);

        $url['host'] = 'www.vd.ch';
        $url['path'] = '/index.php';
        $url['query'] = HttpUtility::buildQueryString($query);

        $cHash = $this->cacheHashCalculator->generateForParameters($url['query']);

        $url['query'] .= $cHash ? '&cHash=' . $cHash : '';
        $url['scheme'] = 'https';

        return HttpUtility::buildUrl($url);
    }

    protected function proceedRecords(): SanitizeVdLinksCommand
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

                    if (
                        strpos($value, 'vd.ch?id=') === false
                        && strpos($value, 'vd.ch/?id=') === false
                        && strpos($value, 'vd.ch/index.php?id=') === false
                    ) {
                        unset($record[$field]);
                        continue;
                    }

                    if ($this->isInputLinkField((array)$this->processedTca[$table]['columns'][$field]) === true) {
                        $linkConfiguration = $this->codecService->decode($value);
                        $linkConfiguration['url'] = $this->normalizeLink((string)$linkConfiguration[0]);

                        if ($linkConfiguration['url'] === '') {
                            unset($record[$field]);
                            continue;
                        }

                        $linkConfiguration['url'] = $this->convertNormalizedLink($linkConfiguration[0]);

                        if ($linkConfiguration['url'] === '' || $linkConfiguration['url'] === $value) {
                            unset($record[$field]);
                            continue;
                        }

                        $url = $this->codecService->encode($linkConfiguration);

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
                        $pattern = '/https?:\/\/(?:www\.)?vd\.ch\/?(?:index\.php)?\?[\w=.?&#\/]+/';

                        if ($isRichText === true) {
                            $delimiter = '"';
                            $pattern = '/\"https?:\/\/(?:www\.)?vd\.ch\/?(?:index\.php)?\?[\w=.?&#\/]+\"/';
                        }

                        preg_match_all($pattern, $value, $matches);

                        if (count($matches[0]) === 0) {
                            unset($record[$field]);
                            continue;
                        }

                        $isSanitized = false;

                        foreach ($matches[0] as $match) {
                            $match = trim($match, '"');
                            $url = $this->normalizeLink($match);

                            if ($url === '') {
                                unset($record[$field]);
                                continue;
                            }

                            $url = $this->convertNormalizedLink($url);

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

    protected function setRecordsToProceed(): SanitizeVdLinksCommand
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
                            '%' . $queryBuilder->escapeLikeWildcards('vd.ch?id=') . '%'
                        )
                    ),
                    $queryBuilder->expr()->like(
                        $field,
                        $queryBuilder->createNamedParameter(
                            '%' . $queryBuilder->escapeLikeWildcards('vd.ch/?id=') . '%'
                        )
                    ),
                    $queryBuilder->expr()->like(
                        $field,
                        $queryBuilder->createNamedParameter(
                            '%' . $queryBuilder->escapeLikeWildcards('vd.ch/index.php?id=') . '%'
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
