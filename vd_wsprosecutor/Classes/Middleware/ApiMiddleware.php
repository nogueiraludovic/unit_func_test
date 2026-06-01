<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdWsprosecutor\Middleware;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Http\NullResponse;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use Vd\VdWsprosecutor\DataHandling\DataHandlerTrait;
use Vd\VdWsprosecutor\Domain\Model\OfficeHour;
use Vd\VdWsprosecutor\Domain\Repository\OfficeHourRepository;

use function count;
use function getenv;
use function in_array;
use function json_decode;

use const JSON_THROW_ON_ERROR;

class ApiMiddleware implements MiddlewareInterface
{
    use DataHandlerTrait;
    use LoggerAwareTrait;

    protected bool $debug = false;
    protected bool $eid = false;
    protected OfficeHourRepository $officeHourRepository;
    protected ServerRequestInterface $request;

    public function __construct(OfficeHourRepository $officeHourRepository)
    {
        // @extensionScannerIgnoreLine
        $this->debug = (bool)GeneralUtility::makeInstance(ExtensionConfiguration::class)->get(
            'vd_wsprosecutor',
            'debug'
        );

        if (($this->logger instanceof LoggerInterface) === false) {
            $this->setLogger(GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__));
        }

        $this->officeHourRepository = $officeHourRepository;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->eid === false && $request->getUri()->getPath() !== '/prosecutor/api') {
            return $handler->handle($request);
        }

        $this->request = $request;
        $this->checkAccess();

        try {
            $cmd = [];
            $data = [];
            $records = $this->getRecords();

            foreach ($records as $record) {
                $existingUid = $this->officeHourRepository->fetchOneByExternalId($record['id']);

                if ((bool)$record['isDeleted'] === true) {
                    if ($existingUid === 0) {
                        continue;
                    }

                    $cmd['tx_vdwsprosecutor_domain_model_officehour'][$existingUid]['delete'] = 1;
                }

                $uid = $existingUid > 0 ? $existingUid : StringUtility::getUniqueId('NEW');

                $data['tx_vdwsprosecutor_domain_model_officehour'][$uid] = (new OfficeHour($record))->getRecord();
            }

            if (count($cmd) === 0 && count($data) === 0) {
                return new NullResponse();
            }

            $this->processDataHandling($cmd, $data);
        } catch (Exception $exception) {
            // Do nothing
        }

        return new NullResponse();
    }

    public function setEid(bool $eid): ApiMiddleware
    {
        $this->eid = $eid;

        return $this;
    }

    protected function accessDeniedAction(): void
    {
        throw new ImmediateResponseException(
            GeneralUtility::makeInstance(ErrorController::class)->accessDeniedAction(
                $this->request,
                'You don\'t have permission to access this service.'
            ),
            1725533431
        );
    }

    protected function checkAccess(): void
    {
        if ($this->hasValidApiKey() === false) {
            $this->accessDeniedAction();
        }

        if ($this->hasValidIp() === false) {
            $this->accessDeniedAction();
        }
    }

    protected function getRecords(): array
    {
        if ((bool)getenv('IS_DDEV_PROJECT') === true) {
            $records = GeneralUtility::getUrl(
                GeneralUtility::getFileAbsFileName('EXT:vd_wsprosecutor/Documentation/example.json')
            );
        } else {
            $records = $this->request->getBody()->getContents();

            $this->logger->info('Raw Data from API: ' . $records);
        }

        return json_decode($records, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function hasValidApiKey(): bool
    {
        $apiKey = (string)getenv('TYPO3_EXT_VD_WSPROSECUTOR_WEBSERVICE_API_KEY');
        $header = $this->request->getHeaderLine('vd-wsprosecutor-key');

        // @extensionScannerIgnoreLine
        if ($this->debug === true) {
            // @extensionScannerIgnoreLine
            $this->logger->debug('Request API value: ' . $header);
            // @extensionScannerIgnoreLine
            $this->logger->debug('Env API value: ' . $apiKey);
        }

        return $apiKey === $header;
    }

    protected function hasValidIp(): bool
    {
        $ipWhiteList = (string)getenv('TYPO3_EXT_VD_WSPROSECUTOR_WEBSERVICE_IP_WHITELIST');

        // @extensionScannerIgnoreLine
        if ($this->debug === true) {
            // @extensionScannerIgnoreLine
            $this->logger->debug('Remote IP: ' . $this->request->getAttribute('normalizedParams')->getRemoteAddress());
            // @extensionScannerIgnoreLine
            $this->logger->debug('IPs Whitelist: ' . $ipWhiteList);
        }

        if ($ipWhiteList === '*') {
            return true;
        }

        return in_array(
            $this->request->getAttribute('normalizedParams')->getRemoteAddress(),
            GeneralUtility::trimExplode(',', $ipWhiteList, true),
            true
        );
    }
}
