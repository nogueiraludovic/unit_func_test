<?php

declare(strict_types=1);

namespace Vd\VdSite\Command\Links;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Routing\SiteMatcher;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_filter;
use function count;
use function preg_match_all;
use function str_replace;
use function strpos;
use function trim;

class SanitizePrestationsLinksCommand extends AbstractLinksCommand
{
    protected function configure(): void
    {
        $this->setDescription('Sanitize "https://prestations.vd.ch/..." links.');
    }

    protected function normalizeLink(string $url): string
    {
        $context = (string)Environment::getContext();
        $subDomain = 'int';

        if ($context === 'Production/Formation' || $context === 'Production/Validation') {
            $subDomain = 'val';
        }

        return str_replace(
            'https://prestations.vd.ch',
            'https://' . $subDomain . '-prestations.vd.ch',
            $url
        );
    }

    protected function proceedRecords(): SanitizePrestationsLinksCommand
    {
        if ((string)Environment::getContext() === 'Production') {
            return $this;
        }

        $counter = 0;

        foreach ($this->records as $table => $records) {
            foreach ($records as $record) {
                $record = array_filter($record);

                foreach ($record as $field => $value) {
                    if ($field === 'CType' || $field === 'pid' || $field === 'uid') {
                        continue;
                    }

                    if (
                        strpos($value, 'https://prestations.vd.ch') === false
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
                        $pattern = '/https?:\/\/(?:prestations\.)?vd\.ch(.*)/';

                        if ($isRichText === true) {
                            $delimiter = '"';
                            $pattern = '/href=\"https?:\/\/(?:prestations\.)?vd\.ch[^"]*\"/';
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

    protected function setRecordsToProceed(): SanitizePrestationsLinksCommand
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
                            '%' . $queryBuilder->escapeLikeWildcards('https://prestations.vd.ch') . '%'
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
