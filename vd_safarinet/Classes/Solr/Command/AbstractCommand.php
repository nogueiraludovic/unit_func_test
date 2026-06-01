<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Solr\Command;

use ApacheSolrForTypo3\Solr\ConnectionManager;
use ApacheSolrForTypo3\Solr\NoSolrConnectionFoundException;
use ApacheSolrForTypo3\Solr\System\Solr\SolrConnection;
use Solarium\Exception\HttpException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdSafarinet\Solr\Provider\InvalidSourceProviderException;
use Vd\VdSafarinet\Solr\Provider\SourceProviderInterface;
use Vd\VdSolr\Factory\DocumentFactory;

class AbstractCommand extends Command
{
    protected function configure(): void
    {
        $this->addOption(
            'force',
            'f',
            InputOption::VALUE_OPTIONAL,
            'Force re-indexation of all items.',
            false
        );
    }

    protected function index(string $sourceProvider, array $options): void
    {
        $solr = GeneralUtility::makeInstance(ConnectionManager::class)->getAllConnections()[0];

        if (($solr instanceof SolrConnection) === false) {
            throw new NoSolrConnectionFoundException(
                'No solr connections found... Please initialize before running this script.'
            );
        }

        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $sourceProvider = GeneralUtility::makeInstance($sourceProvider);

        if (($sourceProvider instanceof SourceProviderInterface) === false) {
            throw new InvalidSourceProviderException(
                'The class "' . $sourceProvider . '" must implement ' . SourceProviderInterface::class . '.'
            );
        }

        try {
            $solr
                ->getWriteService()
                ->addDocuments(DocumentFactory::create($sourceProvider->getDocuments($options)));
        } catch (HttpException $exception) {
            throw new HttpException($exception->getMessage());
        }
    }
}
