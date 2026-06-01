<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Solr\Provider;

use ApacheSolrForTypo3\Solr\Domain\Site\SiteRepository;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdSafarinet\Service\SielService;

abstract class AbstractSourceProvider implements SourceProviderInterface
{
    protected Site $site;
    protected array $documents = [];
    protected string $domain = '';
    protected SielService $sielService;

    public function __construct()
    {
        $this->domain = GeneralUtility::makeInstance(SiteRepository::class)->getFirstAvailableSite()->getDomain();
        $this->site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId(1000001);
        $this->sielService = GeneralUtility::makeInstance(SielService::class);
    }

    abstract public function getDocuments(array $options): array;
}
