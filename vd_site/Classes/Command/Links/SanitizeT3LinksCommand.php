<?php

declare(strict_types=1);

namespace Vd\VdSite\Command\Links;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

use function array_filter;
use function count;
use function parse_str;
use function parse_url;
use function preg_match_all;
use function str_replace;
use function strpos;
use function substr;
use function trim;

class SanitizeT3LinksCommand extends AbstractLinksCommand
{
    protected function configure(): void
    {
        $this->setDescription('Sanitize "t3://" links.');
    }

    protected function normalizeLink(string $url): string
    {
        if (strpos($url, 't3://file?') === 0 || strpos($url, 't3://record?') === 0) {
            return $url;
        }

        if (MathUtility::canBeInterpretedAsInteger($url) === true) {
            if ($url < 1000000) {
                $url += 1000000;
            }

            return 't3://page?uid=' . $url;
        }

        if (strpos($url, 'file:') === 0) {
            return 't3://file?uid=' . substr($url, strpos($url, ':') + 1);
        }

        if (strpos($url, 'record:') === 0) {
            /** @noinspection PhpUnusedLocalVariableInspection */
            [$type, $extension, $table, $uid] = GeneralUtility::trimExplode(':', $url, true, 4);

            switch ($table) {
                case 'tx_news_domain_model_news':
                    return 't3://record?identifier=news&uid=' . $uid;
                case 'tx_vdpressreleases_domain_model_pressrelease':
                case 'tx_vdpressreleases_pressrelease':
                    return 't3://record?identifier=tx_vdpressreleases_pressrelease&uid=' . $uid;
                case 'tx_vdprestations':
                case 'tx_vdprestations_domain_model_prestation':
                    return 't3://record?identifier=tx_vdprestations&uid=' . $uid;
            }
        }

        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $url = parse_url($url);

        parse_str((string)$url['query'], $query);

        if ($query['uid'] === null || $query['uid'] === '' || $query['uid'] > 1000000 || $query['uid'] === 'current') {
            return '';
        }

        $query['uid'] += 1000000;

        unset($query['amp'], $query['no_cache']);

        $url['query'] = HttpUtility::buildQueryString($query);

        return HttpUtility::buildUrl($url);
    }

    protected function proceedRecords(): SanitizeT3LinksCommand
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

                    if ($this->isInputLinkField((array)$this->processedTca[$table]['columns'][$field]) === true) {
                        $linkConfiguration = $this->codecService->decode($value);

                        if (
                            $linkConfiguration['url'] === null
                            || (
                                (int)$linkConfiguration['url'] !== 0
                                && MathUtility::canBeInterpretedAsInteger($linkConfiguration['url']) === false
                                && strpos($linkConfiguration['url'], 'file:') === false
                                && strpos($linkConfiguration['url'], 'record:') === false
                                && strpos($linkConfiguration['url'], 't3://file?uid=') === false
                                && strpos($linkConfiguration['url'], 't3://page?uid=') === false
                                && strpos($linkConfiguration['url'], 't3://record?uid=') === false
                            )
                        ) {
                            unset($record[$field]);
                            continue;
                        }

                        $linkConfiguration['url'] = $this->normalizeLink((string)$linkConfiguration['url']);

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
                        $pattern = '/t3:\/\/page\?[\w=.?&#\/]+\"|\"((file|record)(?::\w+)*:\d+)\"|\"\d+/';

                        if ($isRichText === true) {
                            $delimiter = '"';
                            $pattern = '/\"t3:\/\/page\?[\w=.?&#\/]+\"|\"((file|record)(?::\w+)*:\d+)\"|\"\d+\"/';
                        }

                        preg_match_all($pattern, $value, $matches);

                        if (count($matches[0]) === 0) {
                            unset($record[$field]);
                            continue;
                        }

                        $isSanitized = false;

                        foreach ($matches[0] as $match) {
                            $match = trim($match, '"');

                            if (
                                (int)$match !== 0
                                && MathUtility::canBeInterpretedAsInteger($match) === false
                                && strpos($match, 'file:') === false
                                && strpos($match, 'record:') === false
                                && strpos($match, 't3://file?uid=') === false
                                && strpos($match, 't3://page?uid=') === false
                                && strpos($match, 't3://record?uid=') === false
                            ) {
                                unset($record[$field]);
                                continue;
                            }

                            $url = $this->normalizeLink($match);

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

    protected function setRecordsToProceed(): SanitizeT3LinksCommand
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

                switch ($GLOBALS['TCA'][$table]['columns'][$field]['config']['type']) {
                    case 'input':
                        $statement = $statement->orWhere(
                            $queryBuilder->expr()->neq($field, $queryBuilder->createNamedParameter(''))
                        );
                        break;
                    case 'text':
                        $statement = $statement->orWhere(
                            $queryBuilder->expr()->like(
                                $field,
                                $queryBuilder->createNamedParameter(
                                    '%' . $queryBuilder->escapeLikeWildcards('t3://page?uid=') . '%'
                                )
                            )
                        );
                        break;
                }
            }

            $statement = $statement->execute();

            while ($records = $statement->fetchAllAssociative()) {
                $this->records[$table] = $records;
            }
        }

        return $this;
    }
}
