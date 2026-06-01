<?php

declare(strict_types=1);

namespace Vd\VdSite\Command\Links;

use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;

use function count;
use function getenv;
use function is_array;
use function parse_url;
use function str_replace;
use function strpos;
use function trim;

use const PHP_URL_HOST;

class SanitizeRedirectsCommand extends Command
{
    use FlashMessageTrait;
    use LoggerAwareTrait;

    protected Connection $connection;
    protected static array $oldRedirects = [
        '/actualites',
        '/acv',
        '/aides-financieres-et-soutien-social',
        '/apdi',
        '/autorites',
        '/autorites/',
        '/bcma',
        '/ccf',
        '/ce',
        '/cdc',
        '/cha',
        '/cm',
        '/culture',
        '/dcirh',
        '/def',
        '/deiep',
        '/dfa',
        '/dits',
        '/djes',
        '/dsas',
        '/economie',
        '/environnement',
        '/etat-droit-finances',
        '/formation',
        '/gc',
        '/justice',
        '/mobilite',
        '/mp',
        '/ojv',
        '/population',
        '/sante-soins-et-handicap',
        '/securite',
        '/territoire-et-construction',
        '/tn'
    ];
    protected string $sourceHost = '';

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_redirect');

        if (($this->logger instanceof LoggerInterface) === false) {
            $this->setLogger(GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__));
        }

        $this->sourceHost = parse_url((string)getenv('DGNSI_PUBLIC_URL'), PHP_URL_HOST);
    }

    protected function configure(): void
    {
        $this->setDescription('Sanitize links in redirect records.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();

        $statement = $queryBuilder
            ->select('*')
            ->from('sys_redirect')
            ->where(
                $queryBuilder->expr()->eq('is_regexp', 0),
                $queryBuilder->expr()->eq('source_host', $queryBuilder->createNamedParameter($this->sourceHost)),
                $queryBuilder->expr()->notLike(
                    'target',
                    $queryBuilder->createNamedParameter(
                        '%' . $queryBuilder->escapeLikeWildcards('t3://') . '%'
                    )
                )
            )
            ->execute();

        $queryBuilderForPages = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('pages');
        $queryBuilderForPages->getRestrictions()->removeAll();

        $counter = 0;

        $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);

        while ($rows = $statement->fetchAssociative()) {
            $rows['source_path'] = $this->sanitizePath($this->getUrlParts($rows['source_path']));

            if ($rows['source_path'] === '') {
                $this->markAsDeleted($rows['uid']);

                continue;
            }

            $urlParts = $this->getUrlParts($rows['target']);
            $urlParts['path'] = $this->sanitizePath($urlParts);

            if (
                count($urlParts) === 0
                || ($urlParts['host'] !== null && $urlParts['host'] !== 'www.vd.ch')
            ) {
                try {
                    $target = HttpUtility::buildUrl($urlParts);
                    $response = $requestFactory->request($target);

                    if ($response->getStatusCode() === 200) {
                        $this->update($rows, $target);

                        ++$counter;
                    }
                } catch (RequestException $_) {
                    $this->logger->notice(
                        'Redirect "' . $rows['source_path'] . '" is probably not working anymore.',
                        [
                            'rows' => $rows
                        ]
                    );
                }
            }

            if (strpos($urlParts['path'], '/fileadmin/') === 0) {
                $target = $this->handleFile($urlParts);
            } elseif (strpos($urlParts['path'], '/toutes-les-actualites/news/') === 0) {
                $target = $this->handleNews($urlParts);
            } elseif (
                strpos($urlParts['path'], '/prestation/') === 0
                || strpos($urlParts['path'], '/prestation-detail/prestation/') === 0
            ) {
                $target = $this->handleService($urlParts);
            } else {
                $target = $this->handlePage($urlParts);
            }

            if ($target === '') {
                $this->logger->notice(
                    'Redirect "' . $rows['source_path'] . '" is probably not working anymore.',
                    [
                        'rows' => $rows
                    ]
                );

                continue;
            }

            $this->update($rows, $target);

            ++$counter;
        }

        $this->addFlashMessage($counter);
        $this->deleteOldRedirects();

        return Command::SUCCESS;
    }

    protected function deleteOldRedirects(): void
    {
        foreach (self::$oldRedirects as $oldRedirect) {
            $this->connection->update(
                'sys_redirect',
                [
                    'deleted' => true
                ],
                [
                    'source_path' => $oldRedirect
                ]
            );
        }
    }

    protected function getUrlParts(string $url): array
    {
        $url = str_replace(
            [
                'http://http://',
                'http://https://',
                'https://http://',
                'https://https://'
            ],
            'https://',
            $url
        );

        $urlParts = parse_url($url);

        if (is_array($urlParts) === false) {
            return [];
        }

        return $urlParts;
    }

    protected function handleFile(array $urlParts): string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file');
        $queryBuilder->getRestrictions()->removeAll();

        $uid = $queryBuilder
            ->select('uid')
            ->from('sys_file')
            ->where(
                $queryBuilder->expr()->like(
                    'identifier',
                    $queryBuilder->createNamedParameter('%' . str_replace('/fileadmin/', '', $urlParts['path']) . '%')
                )
            )
            ->execute()
            ->fetchOne();

        if ($uid === false) {
            return '';
        }

        if ($urlParts['fragment'] !== null) {
            return 't3://file?uid=' . $uid . '#' . $urlParts['fragment'];
        }

        return 't3://file?uid=' . $uid;
    }

    protected function handleNews(array $urlParts): string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_news_domain_model_news');
        $queryBuilder->getRestrictions()->removeAll();

        $uid = $queryBuilder
            ->select('uid')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->eq(
                    'path_segment',
                    $queryBuilder->createNamedParameter(
                        str_replace('/toutes-les-actualites/news/', '', $urlParts['path'])
                    )
                )
            )
            ->execute()
            ->fetchOne();

        if ($uid === false) {
            return '';
        }

        if ($urlParts['fragment'] !== null) {
            return 't3://record?identifier=news&uid=' . $uid . '#' . $urlParts['fragment'];
        }

        return 't3://record?identifier=news&uid=' . $uid;
    }

    protected function handlePage(array $urlParts): string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $queryBuilder->getRestrictions()->removeAll();

        $uid = $queryBuilder
            ->select('uid')
            ->from('pages')
            ->where($queryBuilder->expr()->eq('slug', $queryBuilder->createNamedParameter($urlParts['path'])))
            ->execute()
            ->fetchOne();

        if ($uid === false) {
            return '';
        }

        if ($urlParts['fragment'] !== null) {
            return 't3://page?uid=' . $uid . '#' . $urlParts['fragment'];
        }

        return 't3://page?uid=' . $uid;
    }

    protected function handleService(array $urlParts): string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_vdprestations_domain_model_prestation');
        $queryBuilder->getRestrictions()->removeAll();

        $uid = $queryBuilder
            ->select('uid')
            ->from('tx_vdprestations_domain_model_prestation')
            ->where(
                $queryBuilder->expr()->eq(
                    'path_segment',
                    $queryBuilder->createNamedParameter(
                        str_replace(
                            [
                                '/prestation-detail/prestation/',
                                '/prestation/',
                            ],
                            '',
                            $urlParts['path']
                        )
                    )
                )
            )
            ->execute()
            ->fetchOne();

        if ($uid === false) {
            return '';
        }

        if ($urlParts['fragment'] !== null) {
            return 't3://record?identifier=tx_vdprestations&uid=' . $uid . '#' . $urlParts['fragment'];
        }

        return 't3://record?identifier=tx_vdprestations&uid=' . $uid;
    }

    protected function markAsDeleted(int $uid): void
    {
        $this->connection->update(
            'sys_redirect',
            [
                'deleted' => true
            ],
            [
                'uid' => $uid
            ]
        );
    }

    protected function sanitizePath(array $urlParts): string
    {
        if ($urlParts['path'] === null) {
            return '';
        }

        return '/' . trim($urlParts['path'], '/');
    }

    protected function update(array $record, string $target): void
    {
        if ($this->sourceHost !== '') {
            $data['source_host'] = $this->sourceHost;
        }

        $data['source_path'] = $record['source_path'];
        $data['target'] = $target;

        $this->connection->update(
            'sys_redirect',
            $data,
            [
                'uid' => $record['uid']
            ]
        );
    }
}
