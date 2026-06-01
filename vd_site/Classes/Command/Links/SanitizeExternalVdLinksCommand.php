<?php

declare(strict_types=1);

namespace Vd\VdSite\Command\Links;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Routing\RouteNotFoundException;
use TYPO3\CMS\Core\Routing\SiteMatcher;
use TYPO3\CMS\Core\Routing\SiteRouteResult;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_filter;
use function count;
use function getenv;
use function parse_str;
use function parse_url;
use function preg_match_all;
use function str_replace;
use function strpos;
use function trim;

use const PHP_URL_FRAGMENT;

class SanitizeExternalVdLinksCommand extends AbstractLinksCommand
{
    protected SiteMatcher $matcher;
    protected Site $site;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $finder = GeneralUtility::makeInstance(SiteFinder::class);

        $this->matcher = GeneralUtility::makeInstance(SiteMatcher::class, $finder);
        $this->site = $finder->getSiteByPageId(1000001);
    }

    protected function configure(): void
    {
        $this->setDescription('Sanitize "https://www.vd.ch/..." links.');
    }

    protected function getPageArguments(string $url): ?PageArguments
    {
        if ((string)Environment::getContext() !== 'Production') {
            $url = str_replace(
                [
                    'http://vd.ch/',
                    'http://www.vd.ch/',
                    'https://vd.ch/',
                    'https://www.vd.ch/'
                ],
                (string)getenv('DGNSI_PUBLIC_URL'),
                $url
            );
        }

        try {
            $request = (new ServerRequest($url));

            /** @var SiteRouteResult $routeResult */
            $routeResult = $this->matcher->matchRequest($request);

            $site = $routeResult->getSite();

            if (($site instanceof Site) === false) {
                return null;
            }

            $language = $routeResult->getLanguage();

            if (($language instanceof SiteLanguage) === false) {
                return null;
            }

            $request = $request->withAttribute('site', $site);
            $request = $request->withAttribute('language', $language);
            $request = $request->withAttribute('routing', $routeResult);

            return $this->site->getRouter()->matchRequest($request, $routeResult);
        } catch (RouteNotFoundException $_) {
        }

        return null;
    }

    protected function normalizeLink(string $url): string
    {
        if ((bool)strpos($url, 'fileadmin/') === true) {
            /** @noinspection CallableParameterUseCaseInTypeContextInspection */
            $url = parse_url($url);
            $url = str_replace('/fileadmin', '', $url['path']);

            $queryBuilder = $this->connectionPool->getQueryBuilderForTable('sys_file');
            $queryBuilder->getRestrictions()->removeAll();

            $uid = $queryBuilder
                ->select('uid')
                ->from('sys_file')
                ->where($queryBuilder->expr()->eq('identifier', $queryBuilder->createNamedParameter($url)))
                ->execute()
                ->fetchOne();

            if ($uid === false) {
                return '';
            }

            return 't3://file?uid=' . $uid;
        }

        if ((bool)strpos($url, 'go.to') === true) {
            /** @noinspection CallableParameterUseCaseInTypeContextInspection */
            $url = parse_url($url);

            parse_str((string)$url['query'], $query);

            return 't3://record?identifier=tx_vdprestations&uid=' . $query['prestation'];
        }

        if ((bool)strpos($url, 'tx_powermail_pi1') === true) {
            /** @noinspection CallableParameterUseCaseInTypeContextInspection */
            $url = parse_url($url);

            parse_str((string)$url['query'], $query);

            return 't3://record?identifier=tx_vdcontactservice_domain_model_service&uid='
                . $query['tx_powermail_pi1']['uid'];
        }

        $pageArguments = $this->getPageArguments($url);

        if ($pageArguments === null) {
            return '';
        }

        $routeArguments = $pageArguments->getRouteArguments();

        if ($routeArguments['tx_powermail_pi1'] !== null) {
            return 't3://record?identifier=tx_vdcontactservice_domain_model_service&uid=' .
                $routeArguments['tx_powermail_pi1']['uid'];
        }

        if ($routeArguments['tx_vdnews_pi1'] !== null) {
            if ($routeArguments['tx_vdnews_pi1']['category'] !== null) {
                return 't3://record?identifier=news_category&uid=' . $routeArguments['tx_vdnews_pi1']['category'];
            }

            return 't3://record?identifier=news&uid=' . $routeArguments['tx_vdnews_pi1']['news'];
        }

        if ($routeArguments['tx_vdpressreleases_pressrelease'] !== null) {
            return 't3://record?identifier=tx_vdpressreleases_pressrelease&uid='
                . $routeArguments['tx_vdpressreleases_pressrelease']['pressRelease'];
        }

        if ($routeArguments['tx_vdprestations_pi4'] !== null) {
            return 't3://record?identifier=tx_vdprestations&uid='
                . $routeArguments['tx_vdprestations_pi4']['prestation'];
        }

        if ((bool)strpos($url, '?id=') === true) {
            /** @noinspection CallableParameterUseCaseInTypeContextInspection */
            $url = parse_url($url);

            parse_str((string)$url['query'], $query);

            $fragment = $url['fragment'];
            $uid = $query['id'] ?? 1000001;
        } else {
            $fragment = parse_url($url, PHP_URL_FRAGMENT);
            $uid = $pageArguments->getPageId();
        }

        return 't3://page?uid=' . $uid . ($fragment !== null ? '#' . $fragment : '');
    }

    protected function proceedRecords(): SanitizeExternalVdLinksCommand
    {
        $counter = 0;

        foreach ($this->records as $table => $records) {
            foreach ($records as $record) {
                $record = array_filter($record);

                foreach ($record as $field => $value) {
                    if ($field === 'CType' || $field === 'pid' || $field === 'uid') {
                        continue;
                    }

                    if (
                        strpos($value, 'http://vd.ch') === false
                        && strpos($value, 'http://www.vd.ch') === false
                        && strpos($value, 'https://vd.ch') === false
                        && strpos($value, 'https://www.vd.ch') === false
                    ) {
                        unset($record[$field]);
                        continue;
                    }

                    if ($this->isInputLinkField((array)$this->processedTca[$table]['columns'][$field]) === true) {
                        $linkConfiguration = $this->codecService->decode($value);

                        if ($linkConfiguration['url'] === '') {
                            unset($record[$field]);
                            continue;
                        }

                        $linkConfiguration['url'] = $this->normalizeLink((string)$linkConfiguration['url']);

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
                        $pattern = '/https?:\/\/(?:www\.)?vd\.ch[^"]*/';

                        if ($isRichText === true) {
                            $delimiter = '"';
                            $pattern = '/href=\"https?:\/\/(?:www\.)?vd\.ch[^"]*\"/';
                        }

                        preg_match_all($pattern, $value, $matches);

                        if (count($matches[0]) === 0) {
                            unset($record[$field]);
                            continue;
                        }

                        $isSanitized = false;

                        foreach ($matches[0] as $match) {
                            $match = trim(str_replace('href=', '', $match), '"');
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

    protected function setRecordsToProceed(): SanitizeExternalVdLinksCommand
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
                            '%' . $queryBuilder->escapeLikeWildcards('http://vd.ch') . '%'
                        )
                    ),
                    $queryBuilder->expr()->like(
                        $field,
                        $queryBuilder->createNamedParameter(
                            '%' . $queryBuilder->escapeLikeWildcards('http://www.vd.ch') . '%'
                        )
                    ),
                    $queryBuilder->expr()->like(
                        $field,
                        $queryBuilder->createNamedParameter(
                            '%' . $queryBuilder->escapeLikeWildcards('https://vd.ch') . '%'
                        )
                    ),
                    $queryBuilder->expr()->like(
                        $field,
                        $queryBuilder->createNamedParameter(
                            '%' . $queryBuilder->escapeLikeWildcards('https://www.vd.ch') . '%'
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
