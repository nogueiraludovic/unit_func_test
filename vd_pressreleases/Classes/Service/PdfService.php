<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\Service;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use Vd\VdPressreleases\Domain\Model\PressRelease;

class PdfService
{
    protected UriBuilder $uriBuilder;

    public function __construct(UriBuilder $uriBuilder)
    {
        $this->uriBuilder = $uriBuilder;
    }

    public function generate(PressRelease $pressRelease): string
    {
        $argumentName = $pressRelease->isHidden() === true ? 'pressRelease_preview' : 'pressRelease';

        $arguments = [
            'tx_vdpressreleases_pressrelease[' . $argumentName . ']' => $pressRelease->getUid(),
            'tx_web2pdf_pi1[argument]' => 'printPage',
            'tx_web2pdf_pi1[controller]' => 'Pdf'
        ];

        $queryParams = $this->getRequest()->getQueryParams();

        if ((bool)$queryParams['no_cache'] === true) {
            $arguments['no_cache'] = true;
        }

        if ((bool)$queryParams['isPreview'] === true) {
            $arguments['isPreview'] = true;
        }

        $response = GeneralUtility::makeInstance(RequestFactory::class)
            ->request(
                $this->uriBuilder
                    ->reset()
                    ->setArguments($arguments)
                    ->setCreateAbsoluteUri(true)
                    ->setTargetPageType(1976)
                    ->buildFrontendUri(),
                'GET',
                [
                    'headers' => [
                        'accept' => 'application/pdf'
                    ]
                ]
            );

        if ($response->getStatusCode() !== 200) {
            return '';
        }

        $fileName = 'typo3temp/assets/pdfs/' . $pressRelease->getPathSegment() . '.pdf';

        GeneralUtility::writeFileToTypo3tempDir(
            Environment::getPublicPath() . '/' . $fileName,
            $response->getBody()->getContents()
        );

        return $fileName;
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
