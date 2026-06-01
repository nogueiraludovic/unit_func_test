<?php

declare(strict_types=1);

namespace Vd\VdContactService\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Vd\VdContactService\Domain\Repository\ServiceContactRepository;
use Vd\VdMunicipalities\Domain\Repository\MunicipalityRepository;
use Vd\VdMunicipalities\Domain\Repository\PageRepository;

use function count;
use function in_array;
use function trim;

class ServiceContactController extends ActionController
{
    protected MunicipalityRepository $municipalityRepository;
    protected PageRepository $pageRepository;
    protected ServiceContactRepository $serviceContactRepository;

    public function injectMunicipalityRepository(MunicipalityRepository $municipalityRepository): void
    {
        $this->municipalityRepository = $municipalityRepository;
    }

    public function injectPageRepository(PageRepository $pageRepository): void
    {
        $this->pageRepository = $pageRepository;
    }

    public function injectServiceContactRepository(ServiceContactRepository $serviceContactRepository): void
    {
        $this->serviceContactRepository = $serviceContactRepository;
    }

    public function searchAction(string $term = ''): void
    {
        $matches = [];
        $term = trim($term);

        if ($term !== '') {
            $municipalities = $this->municipalityRepository->search($term);
            $pages = $this->pageRepository->findByInstitution((int)$this->settings['institution']);

            foreach ($pages as $page) {
                $municipalityUids = GeneralUtility::intExplode(
                    ',',
                    $page['tx_vdmunicipalitiessearch_municipalities'],
                    true
                );

                if ($page['tx_vdmunicipalitiessearch_districts'] !== '') {
                    $districtUids = GeneralUtility::intExplode(',', $page['tx_vdmunicipalitiessearch_districts'], true);

                    foreach ($districtUids as $districtUid) {
                        $districts = $this->municipalityRepository->findByDistrict($districtUid);

                        foreach ($districts as $district) {
                            $municipalityUids[] = $district->getUid();
                        }
                    }
                }

                foreach ($municipalities as $municipality) {
                    if (in_array($municipality->getUid(), $municipalityUids, true) === false) {
                        continue;
                    }

                    $matches[] = [
                        'municipality' => $municipality,
                        'page' => $page
                    ];
                }
            }

            if (count($matches) === 1) {
                HttpUtility::redirect($this->buildUri((int)$matches[0]['page']['uid']));
            }
        }

        $this->view->assignMultiple([
            'matches' => $matches,
            'term' => $term
        ]);
    }

    public function showAction(): void
    {
        $this->view->assignMultiple([
            'page' => $this->getFrontendController()->page,
            'serviceContact' => $this->serviceContactRepository->findByUid($this->getServiceContactUid())
        ]);
    }

    protected function buildUri(int $pageUid): string
    {
        return $this->controllerContext
            ->getUriBuilder()
            ->reset()
            ->setTargetPageUid($pageUid)
            ->build();
    }

    protected function getFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    protected function getServiceContactUid(): int
    {
        return isset($this->settings['serviceContact'])
            ? (int)$this->settings['serviceContact']
            : 0;
    }
}
