<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Controller\Display;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Vd\VdOcosp\Domain\Repository\DomaineRepository;
use Vd\VdOcosp\Domain\Repository\InscriptionRepository;
use Vd\VdOcosp\Mvc\Controller\AbstractController;

class InscriptionController extends AbstractController
{
    protected DomaineRepository $domaineRepository;
    protected InscriptionRepository $inscriptionRepository;

    public function indexAction(string $school = '', string $training = '', string $month = ''): void
    {
        $inscriptions = [];

        if ($school !== '' || $training !== '') {
            $inscriptions = $this->inscriptionRepository->findBySearch($school, $training);
        } elseif (!empty(GeneralUtility::_POST())) {
            $inscriptions = $this->inscriptionRepository->findAll();
        }

        $this->view->assignMultiple([
            'inscriptions' => $inscriptions,
            'domaines' => $this->domaineRepository->findAll(),
            'months' => $this->prepareListOfMonths(),
            'school' => $school,
            'training' => $training,
            'month' => $month
        ]);
    }

    public function injectDomaineRepository(DomaineRepository $domaineRepository): void
    {
        $this->domaineRepository = $domaineRepository;
    }

    public function injectInscriptionRepository(InscriptionRepository $inscriptionRepository): void
    {
        $this->inscriptionRepository = $inscriptionRepository;
    }

    protected function prepareListOfMonths(): array
    {
        $months = [];

        $thisMonth = (int)date('n');
        $thisYear = (int)date('Y');
        $currentMonth = mktime(0, 0, 0, $thisMonth, 1, $thisYear);
        $nextMonth = mktime(0, 0, 0, $thisMonth + 1, 1, $thisYear);
        $monthAfter = mktime(0, 0, 0, $thisMonth + 2, 1, $thisYear);

        $months[$currentMonth . '-' . $nextMonth] = LocalizationUtility::translate(
            'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_frontend.xlf:mois_' . date('n', $currentMonth),
            'vd_ocosp'
        ) . ' ' . date('Y', $currentMonth);
        $months[$nextMonth . '-' . $monthAfter] = LocalizationUtility::translate(
            'LLL:EXT:vd_ocosp/Resources/Private/Language/locallang_frontend.xlf:mois_' . date('n', $nextMonth),
            'vd_ocosp'
        ) . ' ' . date('Y', $nextMonth);

        return $months;
    }
}
