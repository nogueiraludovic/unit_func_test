<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Controller\Display;

use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use Vd\VdCore\Pagination\SlidingWindowPagination;
use Vd\VdCore\Utility\CacheUtility;
use Vd\VdOcosp\Domain\Model\Profession;
use Vd\VdOcosp\Domain\Repository\ProfessionRepository;
use Vd\VdOcosp\Mvc\Controller\AbstractController;

class ProfessionController extends AbstractController
{
    protected ProfessionRepository $professionRepository;

    public function detailAction(Profession $profession): void
    {
        $this->view->assign('profession', $profession);

        CacheUtility::addCacheTagsForRecord('tx_vdocosp_professions', $profession);
    }

    public function indexAction(string $keyword = '', string $consult = '', string $repealed = ''): void
    {
        $hasSearch = $keyword !== '' || $consult !== '' || $repealed !== '';

        if ($hasSearch === true) {
            $professions = $this->professionRepository->findBySearch($keyword, $consult, $repealed);
        } else {
            $professions = $this->professionRepository->findAll();
        }

        $paginator = new QueryResultPaginator($professions, $this->getPage(), 20);
        $pagination = new SlidingWindowPagination($paginator, 10);

        $this->view->assignMultiple([
            'consult' => $consult,
            'hasSearch' => $hasSearch,
            'keyword' => $keyword,
            'pagination' => $pagination,
            'paginator' => $paginator,
            'professions' => $professions,
            'repealed' => $repealed
        ]);
    }

    public function injectProfessionRepository(ProfessionRepository $professionRepository): void
    {
        $this->professionRepository = $professionRepository;
    }
}
