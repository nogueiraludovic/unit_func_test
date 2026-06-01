<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Vd\VdCore\Mvc\PageNotFoundTrait;
use Vd\VdCore\Utility\CacheUtility;
use Vd\VdPrestations\Domain\Model\Prestation;
use Vd\VdPrestations\Domain\Repository\PrestationRepository;
use Vd\VdPrestations\Service\ContentDataService;

use function array_merge;
use function array_search;
use function usort;

class PrestationController extends ActionController
{
    use PageNotFoundTrait;

    protected PrestationRepository $prestationRepository;

    public function injectPrestationRepository(PrestationRepository $prestationRepository): void
    {
        $this->prestationRepository = $prestationRepository;
    }

    public function listAction(): void
    {
        $contentDataService = GeneralUtility::makeInstance(ContentDataService::class)
            ->setContentElement((int)$this->settings['sourceContentElementId']);

        $this->settings = array_merge($this->settings, $contentDataService->getSettings());
        $this->settings['shortListPageId'] = $contentDataService->getPid();

        $prestations = $this->prestationRepository->findByDomainAndTheme(
            (string)$this->settings['domain'],
            (string)$this->settings['theme']
        );

        $this->setSecurity($prestations);
        $this->view->assignMultiple([
            'prestations' => $prestations,
            'settings' => $this->settings
        ]);

        CacheUtility::addCacheTagsForRecords('tx_vdprestations_prestation', $prestations);
    }

    public function shortListAction(): void
    {
        $listOfPrestations = (string)$this->settings['prestations'];

        $prestations = $listOfPrestations !== ''
            ? $this->prestationRepository->findByExternalIds($listOfPrestations)
            : [];
        $prestationsUids = GeneralUtility::trimExplode(',', $listOfPrestations, true);

        // Sort prestations, so they have the same order as defined in backend
        usort($prestations, static function ($a, $b) use ($prestationsUids): int {
            $aPos = array_search($a->getExternalId(), $prestationsUids, true);
            $bPos = array_search($b->getExternalId(), $prestationsUids, true);

            return $aPos - $bPos;
        });

        $this->setSecurity($prestations);
        $this->view->assign('prestations', $prestations);

        CacheUtility::addCacheTagsForRecords('tx_vdprestations_prestation', $prestations);
    }

    public function showAction(Prestation $prestation = null): void
    {
        if ($prestation === null) {
            $this->pageNotFoundAction();
        }

        $this->view->assignMultiple([
            'accessModalities' => $prestation->getSortedAccessModalities(),
            'current_url' => $this->uriBuilder->getRequest()->getRequestUri(),
            'prestation' => $prestation
        ]);

        CacheUtility::addCacheTagsForRecord('tx_vdprestations_prestation', $prestation);
    }

    public function showSearchBarAction(): void
    {
    }

    protected function setSecurity($prestations)
    {
        foreach ($prestations as $key => $prestation) {
            foreach ($prestation->getAccessModalities() as $accessModality) {
                if (
                    $accessModality->getSecurityLevel() === 'LOGIN' ||
                    $accessModality->getSecurityLevel() === 'AUTHORIZATION'
                ) {
                    $prestation->setSecurity('LOGIN');
                    break;
                }
            }

            $prestations[$key] = $prestation;
        }

        return $prestations;
    }
}
