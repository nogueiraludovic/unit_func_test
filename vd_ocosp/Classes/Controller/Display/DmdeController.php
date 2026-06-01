<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Controller\Display;

use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Vd\VdCore\Utility\CacheUtility;
use Vd\VdOcosp\Domain\Model\Dmde;
use Vd\VdOcosp\Domain\Model\Domaine;
use Vd\VdOcosp\Domain\Model\Profession;
use Vd\VdOcosp\Domain\Repository\DiplomeRepository;
use Vd\VdOcosp\Domain\Repository\DmdeRepository;
use Vd\VdOcosp\Domain\Repository\DomaineRepository;
use Vd\VdOcosp\Domain\Repository\InteretRepository;
use Vd\VdOcosp\Domain\Repository\ProfessionRepository;
use Vd\VdOcosp\Mvc\Controller\AbstractController;

use function addslashes;
use function count;
use function in_array;

class DmdeController extends AbstractController
{
    protected DiplomeRepository $diplomeRepository;
    protected DmdeRepository $dmdeRepository;
    protected DomaineRepository $domaineRepository;
    protected InteretRepository $interetRepository;
    protected ProfessionRepository $professionRepository;

    public function detailAction(Dmde $dmde): void
    {
        $this->view->assign('dmde', $dmde);

        CacheUtility::addCacheTagsForRecord('tx_vdocosp_dmde', $dmde);
    }

    public function indexAction(): void
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $domains = $this->domaineRepository->findByDemande(1);
        $interests = $this->interetRepository->findAll();

        $this->view->assignMultiple([
            'domains' => $domains,
            'interests' => $interests
        ]);

        CacheUtility::addCacheTagsForRecords('tx_vdocosp_domaines', $domains);
        CacheUtility::addCacheTagsForRecords('tx_vdocosp_interets', $interests);
    }

    public function initializeIndexAction(): void
    {
        $this->addJsForAutocomplete();
    }

    public function initializeSimpleFormAction(): void
    {
        $this->addJsForAutocomplete();
    }

    public function injectDiplomeRepository(DiplomeRepository $diplomeRepository): void
    {
        $this->diplomeRepository = $diplomeRepository;
    }

    public function injectDmdeRepository(DmdeRepository $dmdeRepository): void
    {
        $this->dmdeRepository = $dmdeRepository;
    }

    public function injectDomaineRepository(DomaineRepository $domaineRepository): void
    {
        $this->domaineRepository = $domaineRepository;
    }

    public function injectInteretRepository(InteretRepository $interetRepository): void
    {
        $this->interetRepository = $interetRepository;
    }

    public function injectProfessionRepository(ProfessionRepository $professionRepository): void
    {
        $this->professionRepository = $professionRepository;
    }

    public function searchAction(
        string $keyword = '',
        Profession $profession = null,
        Domaine $domain = null,
        array $interests = []
    ): void {
        $verifiedInterests = [];

        foreach ($interests as $anInterest) {
            $anInterest = (int)$anInterest;

            if ($anInterest > 0 && !in_array($anInterest, $verifiedInterests, true)) {
                $verifiedInterests[] = $anInterest;
            }
        }

        $interestObjects = [];

        foreach ($verifiedInterests as $verifiedInterest) {
            $interestObjects[] = $this->interetRepository->findByUid($verifiedInterest);
        }

        if (!empty($verifiedInterests)) {
            $results = $this->dmdeRepository->findByInterests($verifiedInterests);
        } elseif ($profession !== null) {
            $results = $this->dmdeRepository->findByProfessionId($profession);
        } else {
            $results = $this->dmdeRepository->findBySearch($keyword, ($domain === null ? 0 : $domain->getUid()), [], true);
        }

        if (count($results) === 0) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_frontend.xlf:title.dmde.search.results.none',
                    'vd_ocosp'
                ),
                '',
                AbstractMessage::ERROR
            );
            $this->redirect('index');
        } elseif (count($results) === 1) {
            $this->redirect('detail', '', 'vdocosp', ['dmde' => $results[0]]);
        }

        $this->view->assignMultiple([
            'domain' => $domain,
            'interests' => $interestObjects,
            'keyword' => $keyword,
            'results' => $results
        ]);
    }

    public function simpleFormAction(): void
    {
    }

    protected function addJsForAutocomplete(): void
    {
        $inlineFooterJs = 'var Tx_VdOcosp_Professions = [];' . LF . LF;
        $professions = $this->professionRepository->findForDmde();

        foreach ($professions as $profession) {
            $fullName = addslashes($profession->getFullName(false));
            $inlineFooterJs .= <<<EOT
Tx_VdOcosp_Professions.push({
    data: '{$profession->getUid()}',
    value: '{$fullName}'
});
EOT;
        }

        $this->assetCollector->addJavaScript(
            'vd-ocosp-professions',
            GeneralUtility::writeJavaScriptContentToTemporaryFile($inlineFooterJs . LF)
        );
    }
}
