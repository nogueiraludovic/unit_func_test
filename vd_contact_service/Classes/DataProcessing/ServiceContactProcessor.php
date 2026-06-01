<?php

declare(strict_types=1);

namespace Vd\VdContactService\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Page\PageLayoutResolver;
use Vd\VdContactService\Domain\Repository\ServiceContactRepository;

use function array_shift;
use function in_array;

class ServiceContactProcessor implements DataProcessorInterface
{
    protected const ALLOWED_BACKEND_LAYOUTS = [
        'pagets__2_col',
        'pagets__2_col_navigation',
        'pagets__2_col_search'
    ];

    protected TypoScriptFrontendController $frontendController;
    protected PageLayoutResolver $pageLayoutResolver;
    protected ServiceContactRepository $serviceContactRepository;
    protected StandaloneView $view;
    protected UriBuilder $uriBuilder;

    public function __construct(
        PageLayoutResolver $pageLayoutResolver,
        ServiceContactRepository $serviceContactRepository,
        StandaloneView $view,
        UriBuilder $uriBuilder
    ) {
        $this->frontendController = $this->getFrontendController();
        $this->pageLayoutResolver = $pageLayoutResolver;
        $this->serviceContactRepository = $serviceContactRepository;
        $this->uriBuilder = $uriBuilder;
        $this->view = $view;
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $settings = $this->frontendController->tmpl->setup['plugin.']['tx_vdcontactservice.']['settings.'];

        $contactFormLink = $this->uriBuilder
            ->reset()
            ->setTargetPageUid((int)$settings['contactFormPid']);

        $processedData['contactFormLink'] = $contactFormLink->buildFrontendUri();

        $serviceContactUid = $this->getServiceContactUid();

        if ($serviceContactUid === 0) {
            return $processedData;
        }

        $serviceContact = $this->serviceContactRepository->findByUid($serviceContactUid);

        if ($serviceContact === null) {
            return $processedData;
        }

        $this->view->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName(
                'EXT:vd_contact_service/Resources/Private/Templates/ServiceContact/Show.html'
            )
        );

        $processedData['serviceContact'] = $this->view
            ->assignMultiple([
                'page' => $this->frontendController->page,
                'serviceContact' => $serviceContact,
                'settings' => $settings
            ])
            ->render();

        $processedData['contactFormLink'] = $contactFormLink
            ->setArguments([
                'tx_powermail_pi1' => [
                    'referer' => $processedData['data']['uid'],
                    'uid' => $serviceContactUid
                ]
            ])
            ->buildFrontendUri();

        return $processedData;
    }

    protected function getFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    protected function getServiceContactUid(): int
    {
        $currentPage = $this->frontendController->page;

        if ((bool)($currentPage['service_contact_hidden'] ?? false) === true) {
            return 0;
        }

        $rootLine = $this->frontendController->rootLine;
        $backendLayout = $this->pageLayoutResolver->getLayoutForPage($currentPage, $rootLine);

        if (in_array($backendLayout, self::ALLOWED_BACKEND_LAYOUTS, true) === false) {
            return 0;
        }

        if (($currentPage['service_contact'] ?? 0) > 0) {
            return $currentPage['service_contact'];
        }

        array_shift($rootLine);

        foreach ($rootLine as $page) {
            $serviceContact = $page['service_contact'] ?? 0;

            if ($serviceContact > 0) {
                return (bool)($page['service_contact_hidden_subpages'] ?? false) === true ? 0 : $serviceContact;
            }
        }

        return 0;
    }
}
