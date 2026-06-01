<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Controller\Display;

use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use Vd\VdCore\Pagination\SlidingWindowPagination;
use Vd\VdCore\Utility\CacheUtility;
use Vd\VdOcosp\Domain\Model\Adresse;
use Vd\VdOcosp\Domain\Repository\AdresseRepository;
use Vd\VdOcosp\Mvc\Controller\AbstractController;

class AdresseController extends AbstractController
{
    protected AdresseRepository $adresseRepository;

    public function detailAction(Adresse $adresse): void
    {
        $this->view->assign('adresse', $adresse);

        CacheUtility::addCacheTagsForRecord('tx_vdocosp_adresses', $adresse);
    }

    public function indexAction(string $keyword = ''): void
    {
        if ($keyword === '') {
            $adresses = $this->adresseRepository->findAll();
        } else {
            $adresses = $this->adresseRepository->findBySearch($keyword);
        }

        $paginator = new QueryResultPaginator($adresses, $this->getPage(), 20);
        $pagination = new SlidingWindowPagination($paginator, 10);

        $this->view->assignMultiple([
            'adresses' => $adresses,
            'keyword' => $keyword,
            'pagination' => $pagination,
            'paginator' => $paginator
        ]);
    }

    public function injectAdresseRepository(AdresseRepository $adresseRepository): void
    {
        $this->adresseRepository = $adresseRepository;
    }
}
