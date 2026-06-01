<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Service;

use Exception;
use GuzzleHttp\Client;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_merge;
use function count;
use function getenv;
use function json_decode;
use function json_encode;
use function strrpos;
use function substr;

use const JSON_THROW_ON_ERROR;
use const LIBXML_NOCDATA;

class SielService
{
    use LoggerAwareTrait;

    protected bool $debug = false;
    protected bool $error = false;

    public function __construct()
    {
        // @extensionScannerIgnoreLine
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('vd_safarinet');
        $this->debug = (bool)($extensionConfiguration['debug'] ?? false || $extensionConfiguration['debugVerbose'] ?? false);

        if (($this->logger instanceof LoggerInterface) === false) {
            $this->setLogger(GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__));
        }
    }

    public function fetchDecision(string $decisionId): array
    {
        return $this->fetch(
            'service/decisionCE/rechercherDecisionParId',
            [
                'idDecision' => $decisionId
            ]
        );
    }

    public function fetchDecisions(array $parameters = []): array
    {
        return $this->fetch('service/decisionCE/rechercherDecisions', $parameters);
    }

    public function fetchGreatCouncilMeeting(string $meetingId): array
    {
        return $this->fetch(
            'service/seanceGC/rechercherSeanceGc',
            [
                'idSeance' => $meetingId
            ]
        );
    }

    public function fetchGreatCouncilMeetings(array $parameters = []): array
    {
        return $this->fetch('service/seanceGC/rechercherSeancesGc', $parameters);
    }

    public function fetchGreatCouncilPoint(string $pointGcId): array
    {
        return $this->fetch(
            'service/seanceGC/rechercherPointSeanceGcParId',
            [
                'idPoint' => $pointGcId
            ]
        );
    }

    public function fetchGroup(string $groupId): array
    {
        return $this->fetch(
            'service/deputes/rechercherGroupeParId',
            [
                'idGroupe' => $groupId
            ]
        );
    }

    public function fetchIndexGreatCouncilMeetings(array $parameters = []): array
    {
        return $this->fetch('indexSeancesGc', $parameters);
    }

    public function fetchIndexGreatCouncilPoints(array $parameters = []): array
    {
        return $this->fetch('indexPointsSeancesGc', $parameters);
    }

    public function fetchLastChanged(array $parameters = []): array
    {
        return $this->fetch('listerChangementsSeancesGc', $parameters);
    }

    public function fetchMeeting(string $meetingId): array
    {
        return $this->fetch(
            'service/decisionCE/rechercherSeanceCe',
            [
                'idSeance' => $meetingId
            ]
        );
    }

    public function fetchMeetings(array $parameters = []): array
    {
        return $this->fetch('service/decisionCE/rechercherSeancesCe', $parameters);
    }

    public function fetchMember(string $memberId): array
    {
        return $this->fetch(
            'service/deputes/rechercherMembreParId',
            [
                'idMembre' => $memberId
            ]
        );
    }

    public function fetchMembersByDistrict(array $parameters = []): array
    {
        return $this->fetch('service/deputes/rechercherMembresParArrondissement', $parameters);
    }

    public function fetchMembersByName(array $parameters = []): array
    {
        return $this->fetch('service/deputes/rechercherMembresParAlpha', $parameters);
    }

    public function fetchMembersByParty(array $parameters = []): array
    {
        return $this->fetch('service/deputes/rechercherMembresParPartiPolitique', $parameters);
    }

    public function fetchObject(string $objectId): array
    {
        return $this->fetch(
            'service/deputes/rechercherObjetParId',
            [
                'idObjet' => $objectId
            ]
        );
    }

    public function hasError(): bool
    {
        return $this->error;
    }

    protected function callDebugResponse(string $endPoint): array
    {
        $endPoint = substr($endPoint, strrpos($endPoint, '/') + 1);
        $response = GeneralUtility::getUrl(
            GeneralUtility::getFileAbsFileName('EXT:vd_safarinet/Resources/Public/Responses/' . $endPoint . '.xml')
        );

        if ($response === false) {
            $this->error = true;

            return [];
        }

        $response = (new SimpleXMLElement($response, LIBXML_NOCDATA))
            ->xpath('//' . ($endPoint === 'rechercherDecisionParId' ? 'decision' : 'return'));
        $response = json_decode(json_encode($response, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

        if ($response === false) {
            $this->error = true;

            return [];
        }

        return count($response) === 1 ? $response[0] : $response;
    }

    protected function callResponse(string $endPoint, array $parameters = []): array
    {
        try {
            $response = $this
                ->getClient()
                ->get(
                    $endPoint,
                    [
                        'query' => $parameters
                    ]
                );

            if ($response->getStatusCode() !== 200) {
                return [];
            }

            $response = (new SimpleXMLElement($response->getBody()->getContents(), LIBXML_NOCDATA));
            $response = json_decode(json_encode($response, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

            if (count($response) === 0) {
                return [];
            }

            $response = isset($response['decision']) === true ? $response['decision'] : $response['return'];
        } catch (Exception $exception) {
            $this->error = true;
            $this->logger->critical(
                '[ERROR] Failed to request "' . $endPoint . '" from SIEL. Reason: ' . $exception->getMessage()
            );
        }

        return $response ?? [];
    }

    protected function fetch(string $endPoint, array $parameters = []): array
    {
        if ((bool)getenv('IS_DDEV_PROJECT') === true) {
            $response = $this->callDebugResponse($endPoint);
        } else {
            $response = $this->callResponse($endPoint, $parameters);
        }

        // @extensionScannerIgnoreLine
        if ($this->debug === true) {
            // @extensionScannerIgnoreLine
            $this->logger->info('[API] Records for endpoint "' . $endPoint . '" correctly fetched', $response);
        }

        return $response;
    }

    protected function getClient(): Client
    {
        $configuration = $GLOBALS['TYPO3_CONF_VARS']['HTTP'];

        unset($configuration['handler']);

        return new Client(
            array_merge(
                $configuration,
                [
                    'auth' => [
                        (string)getenv('DGNSI_ESGATE_USERNAME'),
                        (string)getenv('DGNSI_ESGATE_PASSWORD')
                    ],
                    'base_uri' => (string)getenv('TYPO3_EXT_VD_SAFARINET_SERVICE_URL'),
                    'timeout' => (int)(GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('vd_safarinet', 'timeout') ?? 20),
                    'verify' => false
                ]
            )
        );
    }
}
