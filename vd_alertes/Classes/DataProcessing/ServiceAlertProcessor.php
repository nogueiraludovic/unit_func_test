<?php

declare(strict_types=1);

namespace Vd\VdAlertes\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Vd\VdAlertes\Domain\Repository\ServiceAlertRepository;

use function array_reverse;

class ServiceAlertProcessor implements DataProcessorInterface
{
    protected ServiceAlertRepository $serviceAlertRepository;

    public function __construct(ServiceAlertRepository $serviceAlertRepository)
    {
        $this->serviceAlertRepository = $serviceAlertRepository;
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        if (
            isset($processorConfiguration['if.']) === true
            && $cObj->checkIf($processorConfiguration['if.']) === false
        ) {
            return $processedData;
        }

        $alerts = [];
        $depth = -1;
        $pages = $this->getFrontendController()->rootLine;

        foreach ($pages as $page) {
            /** @noinspection PhpUndefinedMethodInspection */
            $alert = $this->serviceAlertRepository->findOneByPid($page['uid']);

            ++$depth;

            if ($alert === null) {
                continue;
            }

            if ($depth <= $alert->getDepth()) {
                $alerts[] = $alert;
            }
        }

        $alerts = array_reverse($alerts);

        foreach ($alerts as $alert) {
            $processedData['serviceAlerts'][] = $alert;
        }

        return $processedData;
    }

    protected function getFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
