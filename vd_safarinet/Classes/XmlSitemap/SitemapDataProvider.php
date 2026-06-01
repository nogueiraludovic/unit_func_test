<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\XmlSitemap;

use DateTime;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Solarium\Client;
use Solarium\Core\Client\Adapter\Psr18Adapter;
use Solarium\Exception\HttpException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Seo\XmlSitemap\RecordsXmlSitemapDataProvider;

use function array_key_first;
use function getenv;

class SitemapDataProvider extends RecordsXmlSitemapDataProvider
{
    public function generateItems(): void
    {
        switch ($this->config['type']) {
            case 'siel_ce':
                $result = $this->getResult('type:siel_ce');
                break;
            case 'siel_gc':
                $result = $this->getResult('type:siel_gc && subType_stringS:seance');
                break;
            default:
                return;
        }

        if ($result === null) {
            return;
        }

        foreach ($result as $document) {
            $this->items[] = [
                'data' => [
                    array_key_first($this->config['url']['fieldToParameterMap']) => $document->sielId_stringS
                ],
                'lastMod' => DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $document->changed)->getTimestamp(),
                'priority' => 0.5
            ];
        }
    }

    /** @noinspection PhpInternalEntityUsedInspection */
    protected function getClient(): Client
    {
        $client = new Client(
            new Psr18Adapter(
                GeneralUtility::getContainer()->get(ClientInterface::class),
                GeneralUtility::getContainer()->get(RequestFactoryInterface::class),
                GeneralUtility::getContainer()->get(StreamFactoryInterface::class)
            ),
            GeneralUtility::getContainer()->get(EventDispatcherInterface::class),
        );
        $client->getPlugin('postbigrequest');
        $client->clearEndpoints();
        $client->createEndpoint(
            [
                'core' => (string)getenv('SOLR_CORE'),
                'host' => (string)getenv('SOLR_HOST'),
                'key' => 'read',
                'password' => (string)getenv('DGNSI_ESGATE_PASSWORD'),
                'path' => (string)getenv('SOLR_PATH'),
                'port' => (int)getenv('SOLR_PORT'),
                'scheme' => (string)getenv('SOLR_SCHEME'),
                'timeout' => 0,
                'username' => (string)getenv('DGNSI_ESGATE_USERNAME')
            ],
            true
        );

        return $client;
    }

    protected function getResult(string $query)
    {
        $client = $this->getClient();

        try {
            return $client->select(
                $client
                    ->createSelect()
                    ->setFields([
                        'changed',
                        'sielId_stringS'
                    ])
                    ->setQuery($query)
                    ->setRows(99999)
            );
        } catch (HttpException $exception) {
        }

        return null;
    }
}
