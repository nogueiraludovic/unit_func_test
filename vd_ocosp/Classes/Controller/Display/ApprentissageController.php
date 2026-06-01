<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Controller\Display;

use Vd\VdCore\Utility\CacheUtility;
use Vd\VdOcosp\Domain\Repository\DmdeRepository;
use Vd\VdOcosp\Mvc\Controller\AbstractController;

class ApprentissageController extends AbstractController
{
    protected DmdeRepository $dmdeRepository;

    public function indexAction(): void
    {
        $dmdes = $this->dmdeRepository->findAllWithApprenticeship();

        $this->view->assign('professions', $dmdes);

        CacheUtility::addCacheTagsForRecords('tx_vdocosp_dmde', $dmdes);

        foreach ($dmdes as $dmde) {
            CacheUtility::addCacheTagsForRecords('tx_vdocosp_professions', $dmde->getProfessionId());
        }
    }

    public function injectDmdeRepository(DmdeRepository $dmdeRepository): void
    {
        $this->dmdeRepository = $dmdeRepository;
    }
}
