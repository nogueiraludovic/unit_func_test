<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Vd\VdCore\Mvc\PageNotFoundTrait;
use Vd\VdPressreleases\Domain\Model\PressRelease;
use Vd\VdPressreleases\Domain\Repository\PressReleaseRepository;
use Vd\VdPressreleases\Service\PdfService;
use Vd\VdPressreleases\Utility\CacheUtility;

class PressReleaseController extends ActionController
{
    use PageNotFoundTrait;

    protected PdfService $pdfService;
    protected PressReleaseRepository $pressReleaseRepository;

    public function injectPdfService(PdfService $pdfService): void
    {
        $this->pdfService = $pdfService;
    }

    public function injectPressReleaseRepository(PressReleaseRepository $pressReleaseRepository): void
    {
        $this->pressReleaseRepository = $pressReleaseRepository;
    }

    public function showAction(PressRelease $pressRelease = null): void
    {
        if ($pressRelease === null) {
            $arguments = $this->getRequest()->getQueryParams();

            if ((bool)$arguments['no_cache'] === true) {
                $uid = (int)$arguments['tx_vdpressreleases_pressrelease']['pressRelease_preview'];

                if ($uid > 0) {
                    $pressRelease = $this->pressReleaseRepository->findByUid($uid, true, false, false);
                }
            }
        }

        if ($pressRelease === null) {
            $this->pageNotFoundAction();
        }

        $this->view->assignMultiple([
            'pdf' => $this->pdfService->generate($pressRelease),
            'pressRelease' => $pressRelease
        ]);

        CacheUtility::addCacheTagsForPressRelease($pressRelease);
    }

    protected function getFrontendController(): ?TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    protected function initializeAction(): void
    {
        $frontendController = $this->getFrontendController();

        if ($frontendController === null) {
            return;
        }

        static $cacheTagsSet = false;

        if ($cacheTagsSet === false) {
            $cacheTagsSet = true;

            $frontendController->addCacheTags(['tx_vdpressreleases']);
        }
    }
}
