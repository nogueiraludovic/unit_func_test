<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\DataProcessing;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Vd\VdPressreleases\Domain\Repository\PressReleaseRepository;

class PressReleaseProcessor implements DataProcessorInterface
{
    protected PressReleaseRepository $pressReleaseRepository;

    public function __construct(PressReleaseRepository $pressReleaseRepository)
    {
        $this->pressReleaseRepository = $pressReleaseRepository;
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $arguments = $this->getRequest()->getQueryParams();

        if (
            $arguments['tx_vdpressreleases_pressrelease']['pressRelease'] === null
            && $arguments['tx_vdpressreleases_pressrelease']['pressRelease_preview'] === null
        ) {
            return $processedData;
        }

        $uid = (int)(
            $arguments['tx_vdpressreleases_pressrelease']['pressRelease']
                ?: $arguments['tx_vdpressreleases_pressrelease']['pressRelease_preview']
        );

        if ($uid === 0) {
            return $processedData;
        }

        $pressRelease = $this->pressReleaseRepository->findByUid($uid, true, false, false);

        if ($pressRelease === null) {
            return $processedData;
        }

        $processedData['pressReleaseData'] = $pressRelease;

        return $processedData;
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
