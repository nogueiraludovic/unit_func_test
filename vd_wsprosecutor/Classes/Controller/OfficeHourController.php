<?php

declare(strict_types=1);

namespace Vd\VdWsprosecutor\Controller;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Vd\VdWsprosecutor\Domain\Repository\OfficeHourRepository;

use function count;
use function max;

class OfficeHourController extends ActionController
{
    protected AssetCollector $assetCollector;
    protected MetaTagManagerRegistry $metaTagManagerRegistry;
    protected OfficeHourRepository $officeHourRepository;

    public function __construct(
        AssetCollector $assetCollector,
        MetaTagManagerRegistry $metaTagManagerRegistry,
        OfficeHourRepository $officeHourRepository
    ) {
        $this->assetCollector = $assetCollector;
        $this->metaTagManagerRegistry = $metaTagManagerRegistry;
        $this->officeHourRepository = $officeHourRepository;
    }

    public function currentAction(): void
    {
        $groups = $this->officeHourRepository->fetchAllCurrentOffices();
        $hasCantonalGuard = false;
        /** @noinspection NullPointerExceptionInspection */
        $now = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'full')->format('d.m.Y');

        foreach ($groups['MPVd']['Garde cantonale MP'] ?? [] as $officeHour) {
            if ($officeHour['end_shift_date'] !== $now) {
                continue;
            }

            $hasCantonalGuard = true;
        }

        $this->view->assignMultiple([
            'divasSection' => $groups['Divas'],
            'hasCantonalGuard' => $hasCantonalGuard,
            'mpSection' => $groups['MP'],
            'mpVdSection' => $groups['MPVd'],
            'now' => $now,
            'tmSection' => $groups['TM']
        ]);
    }

    public function listAction(): void
    {
        $officeHours = $this->officeHourRepository->fetchAll();

        $this->initializePagination($officeHours);
        $this->view->assignMultiple([
            // @extensionScannerIgnoreLine
            'contentData' => $this->configurationManager->getContentObject()->data,
            'officeHours' => $officeHours,
            'officeHoursCount' => count($officeHours)
        ]);
    }

    protected function getErrorFlashMessage(): bool
    {
        return false;
    }

    protected function initializeListAction(): void
    {
        $this->assetCollector->addJavaScript(
            'vd-wsprosecutor',
            'EXT:vd_wsprosecutor/Resources/Public/JavaScript/vd-wsprosecutor.min.js'
        );
        $this->assetCollector->addStyleSheet(
            'vd-wsprosecutor',
            'EXT:vd_wsprosecutor/Resources/Public/Css/vd-wsprosecutor.min.css'
        );

        $metaTagManager = $this->metaTagManagerRegistry->getManagerForProperty('robots');
        $metaTagManager->addProperty('robots', 'noindex,follow');
    }

    protected function initializePagination(array $items): void
    {
        if ((bool)$this->settings['pagination']['enable'] === false) {
            return;
        }

        $currentPage = 1;

        if ($this->request->hasArgument('page') === true) {
            $currentPage = max((int)$this->request->getArgument('page'), 1);
        }

        $paginator = new ArrayPaginator(
            $items,
            $currentPage,
            (int)($this->settings['pagination']['itemsPerPage'] ?? 10)
        );

        $this->view->assignMultiple([
            'currentPage' => $currentPage,
            'pagination' => new SimplePagination($paginator),
            'paginator' => $paginator,
            'paginationPartial' => $this->settings['pagination']['partial'] ?? 'Default'
        ]);
    }
}
