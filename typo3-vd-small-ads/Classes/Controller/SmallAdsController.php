<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use Vd\VdSmallAds\DataTransferObject\Demand;
use Vd\VdSmallAds\Domain\Repository\SmallAdsRepository;
use Vd\VdSmallAds\Session\SessionStorage;

use function max;

class SmallAdsController extends ActionController
{
    protected string $sessionIdentifier = '';

    public function __construct(
        protected readonly SessionStorage $sessionStorage,
        protected readonly SmallAdsRepository $smallAdsRepository
    ) {
    }

    public function listAction(?Demand $demand = null): ResponseInterface
    {
        $demand = $this->createDemand($demand);
        $smallAds = $this->smallAdsRepository->findDemanded($demand);

        $paginator = new QueryResultPaginator($smallAds, $this->getPage(), 10);

        $this->view->assignMultiple([
            'adTypes' => $this->smallAdsRepository->findAdTypes(),
            'demand' => $demand,
            'objectTypes' => $this->smallAdsRepository->findObjectTypes(),
            'pagination' => new SimplePagination($paginator),
            'paginator' => $paginator,
            'smallAds' => $smallAds
        ]);

        return $this->htmlResponse();
    }

    public function resetAction(): ResponseInterface
    {
        $this->sessionStorage->remove($this->sessionIdentifier);

        return $this->redirect('list', null, null, ['demand' => null]);
    }

    protected function createDemand(?Demand $demand = null): ?Demand
    {
        if (($demand instanceof Demand) === false && $this->sessionStorage->has($this->sessionIdentifier) === true) {
            return $this->sessionStorage->getDemand($this->sessionIdentifier);
        }

        if (($demand instanceof Demand) === true) {
            $this->sessionStorage->set($this->sessionIdentifier, (string)$demand);
        }

        return $demand;
    }

    protected function getPage(): int
    {
        if ($this->request->hasArgument('page') === true) {
            return max((int)$this->request->getArgument('page'), 1);
        }

        return 1;
    }

    protected function initializeAction(): void
    {
        // @extensionScannerIgnoreLine
        $this->sessionIdentifier = 'tx_vdsmallads_' . $this->configurationManager->getContentObject()->data['uid'];
    }
}
