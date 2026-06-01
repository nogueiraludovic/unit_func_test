<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Controller\Display;

use Vd\VdCore\Utility\CacheUtility;
use Vd\VdOcosp\Domain\Repository\DmdeRepository;
use Vd\VdOcosp\Mvc\Controller\AbstractController;

use function header;
use function utf8_decode;

class SalaryController extends AbstractController
{
    protected DmdeRepository $dmdeRepository;

    public function exportAction(): void
    {
        $dmdes = $this->dmdeRepository->findAllWithSalary();

        $this->view->assign('salaries', $dmdes);

        CacheUtility::addCacheTagsForRecords('tx_vdocosp_dmde', $dmdes);

        $output = $this->view->render();

        header('Content-type:application/vnd.ms-excel');
        header('Content-disposition:attachment;filename=salaire.xls');

        echo utf8_decode($output);
        exit;
    }

    public function indexAction(): void
    {
        $dmdes = $this->dmdeRepository->findAllWithSalary();

        $this->view->assign('salaries', $dmdes);

        CacheUtility::addCacheTagsForRecords('tx_vdocosp_dmde', $dmdes);
    }

    public function injectDmdeRepository(DmdeRepository $dmdeRepository): void
    {
        $this->dmdeRepository = $dmdeRepository;
    }
}
