<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdPrestations\Middleware;

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
use TYPO3\CMS\Frontend\Controller\ErrorController;
use Vd\VdPrestations\Persistence\XmlDataMapper;

use function getenv;
use function in_array;

class ApiMiddleware implements MiddlewareInterface
{
    use LoggerAwareTrait;

    protected XmlDataMapper $dataMapper;
    protected bool $debug = false;
    protected bool $eid = false;
    protected ServerRequestInterface $request;

    public function __construct()
    {
        $this->dataMapper = GeneralUtility::makeInstance(XmlDataMapper::class);
        // @extensionScannerIgnoreLine
        $this->debug = (bool)GeneralUtility::makeInstance(ExtensionConfiguration::class)->get(
            'vd_prestations',
            'debug'
        );

        if (($this->logger instanceof LoggerInterface) === false) {
            $this->setLogger(GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__));
        }
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->eid === false && $request->getUri()->getPath() !== '/prestations/api') {
            return $handler->handle($request);
        }

        $this->request = $request;
        $this->checkAccess();

        try {
            $this->dataMapper->consume($request->getBody()->getContents());

            return new NullResponse();
        } catch (Exception $exception) {
            // @extensionScannerIgnoreLine
            $this->logger->error(
                '[Prestation] Global error, code 154360050',
                [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage()
                ]
            );

            throw $exception;
        }
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

    protected function hasValidApiKey(): bool
    {
        $apiKey = (string)getenv('TYPO3_EXT_VD_PRESTATIONS_WEBSERVICE_API_KEY');
        $header = $this->request->getHeaderLine('vd-prestations-key');

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
        $ipWhiteList = (string)getenv('TYPO3_EXT_VD_PRESTATIONS_WEBSERVICE_IP_WHITELIST');

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
