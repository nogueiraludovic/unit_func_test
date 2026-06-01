<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Persistence;

use DOMDocument;
use DOMElement;
use DOMNode;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Vd\VdCore\Authentication\AuthenticationTrait;
use Vd\VdPrestations\Domain\Model\AccessModality;
use Vd\VdPrestations\Domain\Model\Prestation;
use Vd\VdPrestations\Domain\Model\TargetAudience;
use Vd\VdPrestations\Domain\Model\Url;
use Vd\VdPrestations\Domain\Repository\AccessModalityRepository;
use Vd\VdPrestations\Domain\Repository\PrestationRepository;
use Vd\VdPrestations\Domain\Repository\TargetAudienceRepository;
use Vd\VdPrestations\Domain\Repository\UrlRepository;
use Vd\VdPrestations\Service\SolrService;
use Vd\VdPrestations\Utility\ConfigurationUtility;
use Vd\VdPrestations\Utility\StringUtility;
use Vd\VdPrestations\Utility\XmlValidatorUtility;

use function ucfirst;

class XmlDataMapper
{
    use AuthenticationTrait;
    use LoggerAwareTrait;

    protected const xmlToExtbasePropertyMapping = [
        'idMetierPrestation' => 'externalId',
        'titre' => 'title',
        'description' => 'description',
        'emolument' => 'emolument',
        'conditionsPrealables' => 'prerequisites',
        'resultatPrestation' => 'result',
        'actionBeneficiaire' => 'actionClient',
        'actionPrestataire' => 'actionService',
        'domaineMetier' => 'domainName',
        'themeMetier' => 'themeName',
        'lienAide' => 'helpLink'
    ];

    protected AccessModalityRepository $accessModailityRepository;
    protected ConfigurationManager $configurationManager;
    protected bool $debug = false;
    protected PersistenceManager $persistenceManager;
    protected PrestationRepository $prestationRepository;
    protected bool $solrActive = true;
    protected SolrService $solrService;
    protected int $storagePid = 0;
    protected TargetAudienceRepository $targetAudienceRepository;
    protected UrlRepository $urlRepository;

    public function __construct(bool $solr = true)
    {
        $this->accessModailityRepository = GeneralUtility::makeInstance(AccessModalityRepository::class);
        $this->configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        // @extensionScannerIgnoreLine
        $this->debug = (bool)GeneralUtility::makeInstance(ExtensionConfiguration::class)->get(
            'vd_prestations',
            'debug'
        );

        if ($this->logger === null) {
            $this->setLogger(GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__));
        }

        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $this->prestationRepository = GeneralUtility::makeInstance(PrestationRepository::class);

        $this->solrActive = $solr;

        if ($solr === true) {
            $this->solrService = GeneralUtility::makeInstance(SolrService::class);
        }

        $this->storagePid = (int)ConfigurationUtility::getConfigurationOption('storagePid', 0);
        $this->targetAudienceRepository = GeneralUtility::makeInstance(TargetAudienceRepository::class);
        $this->urlRepository = GeneralUtility::makeInstance(UrlRepository::class);
    }

    public function consume(string $xmlMessage): void
    {
        // @extensionScannerIgnoreLine
        if ($this->debug === true) {
            // @extensionScannerIgnoreLine
            $this->logger->debug('[Prestation] Start, code: 154360010', ['xml' => $xmlMessage]);
        }

        $xmlDocument = new DOMDocument();
        $xmlDocument->loadXML($xmlMessage);

        XmlValidatorUtility::validateXml($xmlDocument);

        foreach ($xmlDocument->getElementsByTagName('evenement') as $event) {
            $xmlPrestation = $event->getElementsByTagName('prestation')->item(0);
            $existingPrestation = $this->prestationRepository->findByExternalId(
                $xmlPrestation->getElementsByTagName('idMetierPrestation')->item(0)->nodeValue
            );
            $eventType = $event->getAttribute('type');

            // @extensionScannerIgnoreLine
            $this->logger->info(
                '[Prestation] Start importing prestation ' . $xmlPrestation->getElementsByTagName('idMetierPrestation')->item(0)->nodeValue . ', code 154360060',
                [
                    'type' => $eventType,
                    // @extensionScannerIgnoreLine
                    'xml' => $this->debug === true ? $xmlPrestation : ''
                ]
            );

            switch ($eventType) {
                case 'AJOUT':
                case 'MODIFICATION':
                    $newPrestation = $this->map($xmlPrestation, $eventType);

                    if ($existingPrestation === null) {
                        $this->prestationRepository->add($newPrestation);
                        $this->persistenceManager->persistAll();
                        $this->mapRelations($newPrestation, $xmlPrestation, $eventType);

                        if ($this->solrActive) {
                            $this->solrService->update($newPrestation);
                        }
                    } else {
                        $existingPrestation->setHidden(false);
                        $existingPrestation->updateFrom($newPrestation);
                        $this->prestationRepository->update($existingPrestation);
                        $this->persistenceManager->persistAll();
                        $this->mapRelations($existingPrestation, $xmlPrestation, $eventType);

                        if ($this->solrActive) {
                            $this->solrService->update($existingPrestation);
                        }
                    }
                    break;
                case 'SUPPRESSION':
                    if ($existingPrestation !== null) {
                        foreach ($existingPrestation->getAccessModalities() as $accessModality) {
                            $accessModality->setHidden(true);
                            $this->accessModailityRepository->update($accessModality);
                        }

                        foreach ($existingPrestation->getRelatedPages() as $relatedPage) {
                            $relatedPage->setHidden(true);
                            $this->urlRepository->update($relatedPage);
                        }

                        foreach ($existingPrestation->getLegalReferences() as $legalReference) {
                            $legalReference->setHidden(true);
                            $this->urlRepository->update($legalReference);
                        }

                        $existingPrestation->setHidden(true);
                        $this->prestationRepository->update($existingPrestation);
                        $this->persistenceManager->persistAll();

                        if ($this->solrActive) {
                            $this->solrService->remove($existingPrestation);
                        }
                    } else {
                        // @extensionScannerIgnoreLine
                        $this->logger->error('[Prestation] Unable to delete a prestation, not found, code 154360055');
                    }
                    break;
            }

            // @extensionScannerIgnoreLine
            $this->logger->info('[Prestation] End importing prestation, code 154360100');
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $statement = $queryBuilder
            ->select('pid')
            ->from('tt_content')
            ->where($queryBuilder->expr()->like('list_type', $queryBuilder->createNamedParameter('%vdprestations%')))
            ->execute();

        $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        $dataHandler->start([], [], $this->getFakeAdminUser('_prestation_'));

        $pids = [];

        while ($rows = $statement->fetchAssociative()) {
            $dataHandler->clear_cacheCmd($pids[] = (int)$rows['pid']);
        }

        // @extensionScannerIgnoreLine
        if ($this->debug === true) {
            // @extensionScannerIgnoreLine
            $this->logger->debug('[Prestation] Cache clear for PID, code 154360110', $pids);
        }
    }

    public function mapRelations(Prestation $prestation, DOMNode $xmlPrestation, string $type): void
    {
        $prestation->setTargetAudience($this->extractTargetAudiences($xmlPrestation));
        $prestation->setAccessModalities($this->extractAccessModalities($xmlPrestation));
        $prestation->setRelatedPages($this->extractRelatedPages($xmlPrestation));
        $prestation->setRelatedPrestations($this->extractRelatedPrestations($xmlPrestation));
        $prestation->setLegalReferences($this->extractLegalReferences($xmlPrestation));

        $this->prestationRepository->merge(
            $prestation,
            $this->prestationRepository->findByExternalId($prestation->getExternalId())
        );

        $this->persistenceManager->persistAll();
    }

    protected function calculateSlug(Prestation $prestation): string
    {
        $slugHelper = GeneralUtility::makeInstance(
            SlugHelper::class,
            'tx_vdprestations_domain_model_prestation',
            'path_segment',
            $GLOBALS['TCA']['tx_vdprestations_domain_model_prestation']['columns']['path_segment']['config']
        );

        return $slugHelper->generate(
            [
                'title' => $prestation->getTitle()
            ],
            (int)ConfigurationUtility::getConfigurationOption('storagePid', 0)
        );
    }

    protected function extractAccessModalities(DOMElement $xmlPrestation): ObjectStorage
    {
        $accessModalities = new ObjectStorage();
        $nodes = $xmlPrestation->getElementsByTagName('modaliteAcces');

        /** @var DOMElement $node */
        foreach ($nodes as $node) {
            $accessModality = new AccessModality();
            $accessModality->setType($node->attributes['typeModaliteAcces']->value);
            $accessModality->setHowto($node->getElementsByTagName('marcheASuivre')->item(0)->nodeValue ?? '');
            $accessModality->setUrl($node->getElementsByTagName('lienAccesPrestation')->item(0)->nodeValue ?? '');
            $accessModality->setHash(AccessModalityRepository::generateHash($accessModality));
            $accessModality->setAdditionalInformations($node->getElementsByTagName('InformationsComplementaires')->item(0)->nodeValue ?? '');
            $accessModality->setAverageDelay($node->getElementsByTagName('delaiMoyenTraitementDemande')->item(0)->nodeValue ?? '');
            $accessModality->setCost($node->getElementsByTagName('coutTraitementDemande')->item(0)->nodeValue ?? '');
            $accessModality->setRequiredDocuments($node->getElementsByTagName('documentsObligatoires')->item(0)->nodeValue ?? '');
            $accessModality->setSecurityLevel($node->getElementsByTagName('niveauSecurite')->item(0)->nodeValue ?? '');
            $ePayment = StringUtility::stringToBool($node->getElementsByTagName('ePayment')->item(0)->nodeValue);
            $accessModality->setEpayment($ePayment);
            $externalLink = StringUtility::stringToBool($node->getElementsByTagName('lienExterne')->item(0)->nodeValue);
            $accessModality->setExternalLink($externalLink);
            $accessModality->setPid($this->storagePid);
            $this->persistenceManager->add($accessModality);
            $accessModalities->attach($accessModality);
        }

        $this->persistenceManager->persistAll();

        return $accessModalities;
    }

    protected function extractLegalReferences(DOMElement $xmlPrestation): ObjectStorage
    {
        $urls = new ObjectStorage();
        $nodes = $xmlPrestation->getElementsByTagName('referencesLegaux');

        /** @var DOMElement $node */
        foreach ($nodes as $node) {
            $url = new Url();
            $url->setTitle($node->getElementsByTagName('titreReferenceLegale')->item(0)->nodeValue);
            $url->setUrl($node->getElementsByTagName('urlReferenceLegale')->item(0)->nodeValue ?? '');
            $url->setHash(UrlRepository::generateHash($url));
            $url->setPid($this->storagePid);
            $url->setFieldname('legal_references');
            $this->persistenceManager->add($url);
            $urls->attach($url);
        }

        $this->persistenceManager->persistAll();

        return $urls;
    }

    protected function extractRelatedPages(DOMElement $xmlPrestation): ObjectStorage
    {
        $urls = new ObjectStorage();
        $nodes = $xmlPrestation->getElementsByTagName('pagesEnRelation');

        /** @var DOMElement $node */
        foreach ($nodes as $node) {
            $url = new Url();
            $url->setTitle($node->getElementsByTagName('titrePage')->item(0)->nodeValue);
            $url->setUrl($node->getElementsByTagName('urlPage')->item(0)->nodeValue);
            $url->setHash(UrlRepository::generateHash($url));
            $url->setFieldname('related_pages');
            $url->setPid($this->storagePid);
            $this->persistenceManager->add($url);
            $urls->attach($url);
        }

        $this->persistenceManager->persistAll();

        return $urls;
    }

    protected function extractRelatedPrestations(DOMElement $xmlPrestation): ObjectStorage
    {
        $prestations = new ObjectStorage();
        $nodes = $xmlPrestation->getElementsByTagName('prestationsEnRelation');
        $prestationRepository = GeneralUtility::makeInstance(PrestationRepository::class);

        /** @var DOMElement $node */
        foreach ($nodes as $node) {
            $attachedPrestation = $node->getElementsByTagName('idMetier')->item(0)->nodeValue;
            $existingPrestation = $prestationRepository->findByExternalId($attachedPrestation);

            if ($existingPrestation !== null) {
                $prestations->attach($existingPrestation);
            }
        }

        $this->persistenceManager->persistAll();

        return $prestations;
    }

    protected function extractTargetAudiences(DOMElement $xmlPrestation): ObjectStorage
    {
        $targetAudiences = new ObjectStorage();
        $targetAudiencesNodes = $xmlPrestation->getElementsByTagName('publicsCibles');

        /** @var DOMElement $node */
        foreach ($targetAudiencesNodes as $node) {
            $name = $node->getElementsByTagName('population')->item(0)->nodeValue;
            $targetAudience = $this->targetAudienceRepository->findByName($name);
            if ($targetAudience === null) {
                $targetAudience = new TargetAudience();
                $targetAudience->setName($name);
                $targetAudience->setPid($this->storagePid);
                $this->persistenceManager->add($targetAudience);
            } else {
                $this->persistenceManager->update($targetAudience);
            }
            $targetAudiences->attach($targetAudience);
        }

        $this->persistenceManager->persistAll();

        return $targetAudiences;
    }

    protected function map(DOMNode $xmlPrestation, string $type): Prestation
    {
        $prestation = new Prestation();

        foreach (self::xmlToExtbasePropertyMapping as $tagName => $property) {
            $elements = $xmlPrestation->getElementsByTagName($tagName);

            if ($elements->count() > 0) {
                $prestation->{'set' . ucfirst($property)}(
                    $xmlPrestation->getElementsByTagName($tagName)->item(0)->nodeValue ?? ''
                );
            }
        }

        $prestation->setPid($this->storagePid);

        // Slug generation if:
        // 1. The record is new (first import).
        // 2. The record exists but its slug is empty.
        if ($type === 'AJOUT' || ($type === 'MODIFICATION' && $prestation->getPathSegment() === '')) {
            $prestation->setPathSegment($this->calculateSlug($prestation));
        }

        return $prestation;
    }
}
