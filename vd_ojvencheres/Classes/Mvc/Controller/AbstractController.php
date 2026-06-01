<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Mvc\Controller;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Vd\VdCore\Mvc\PageNotFoundTrait;
use Vd\VdOjvencheres\Domain\Model\Dto\AbstractDemand;
use Vd\VdOjvencheres\Domain\Repository\ItemCategoryRepository;
use Vd\VdOjvencheres\Service\FilterService;
use Vd\VdOjvencheres\Session\SessionStorage;

use function array_unique;
use function max;

abstract class AbstractController extends ActionController
{
    use PageNotFoundTrait;

    protected AssetCollector $assetCollector;
    protected ConnectionPool $connection;
    protected FilterService $filterService;
    protected ItemCategoryRepository $itemCategoryRepository;
    protected SessionStorage $sessionStorage;

    public function injectAssetCollector(AssetCollector $assetCollector): void
    {
        $this->assetCollector = $assetCollector;
    }

    public function injectConnectionPool(ConnectionPool $connection): void
    {
        $this->connection = $connection;
    }

    public function injectFilterService(FilterService $filterService): void
    {
        $this->filterService = $filterService;
    }

    public function injectItemCategoryRepository(ItemCategoryRepository $itemCategoryRepository): void
    {
        $this->itemCategoryRepository = $itemCategoryRepository;
    }

    public function injectSessionStorage(SessionStorage $sessionStorage): void
    {
        $this->sessionStorage = $sessionStorage;
    }

    public function resetAction(): void
    {
        $this->sessionStorage->reset();
        $this->redirect('list', null, null, ['demand' => null]);
    }

    protected function createDemand(AbstractDemand $demand = null): ?AbstractDemand
    {
        if ($demand === null && $this->sessionStorage->has('demand') === true) {
            return $this->sessionStorage->getDemand();
        }

        if ($demand !== null) {
            $this->sessionStorage->push(['demand' => (string)$demand]);
        }

        return $demand;
    }

    protected function getFrontendController(): ?TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    protected function getPage(): int
    {
        if ($this->request->hasArgument('page') === true) {
            return max((int)$this->request->getArgument('page'), 1);
        }

        return 1;
    }

    protected function getStoragePids(): array
    {
        $storagePids = [];
        $tables = [
            'tx_vdojvencheres_domain_model_item',
            'tx_vdojvencheres_domain_model_itemcategory',
            'tx_vdojvencheres_domain_model_itemcondition',
            'tx_vdojvencheres_domain_model_lot',
            'tx_vdojvencheres_domain_model_office',
            'tx_vdojvencheres_domain_model_sale',
            'tx_vdojvencheres_domain_model_salecategory',
            'tx_vdojvencheres_domain_model_salecondition',
            'tx_vdojvencheres_domain_model_saledate'
        ];

        foreach ($tables as $table) {
            $queryBuilder = $this->connection->getQueryBuilderForTable($table);
            $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

            $statement = $queryBuilder
                ->selectLiteral('DISTINCT `pid`')
                ->from($table)
                ->execute();

            while ($rows = $statement->fetchAssociative()) {
                $storagePids[] = $rows['pid'];
            }
        }

        return array_unique($storagePids);
    }

    protected function initializeAction(): void
    {
        $frontendController = $this->getFrontendController();

        if ($frontendController === null) {
            return;
        }

        static $cacheTagsSet = false;

        if ($cacheTagsSet === false) {
            $cacheTagsSet = true;

            $frontendController->addCacheTags(['tx_vdojvencheres']);
        }
    }

    protected function initializeListAction(): void
    {
        $this->assetCollector->addJavaScript(
            'vd-ojvencheres',
            'EXT:vd_ojvencheres/Resources/Public/JavaScript/vd-ojvencheres.min.js'
        );
    }

    protected function initializePagination(QueryResultInterface $items): void
    {
        if ((bool)$this->settings['hidePagination'] === true) {
            return;
        }

        $paginator = new QueryResultPaginator(
            $items,
            $this->getPage(),
            (int)($this->settings['list']['paginate']['itemsPerPage'] ?? 10)
        );
        $pagination = new SimplePagination($paginator);

        $this->view->assignMultiple([
            'pagination' => $pagination,
            'paginator' => $paginator
        ]);
    }
}
