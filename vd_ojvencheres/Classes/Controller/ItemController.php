<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Controller;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use Vd\VdCore\Utility\CacheUtility;
use Vd\VdOjvencheres\Domain\Model\Dto\ItemDemand;
use Vd\VdOjvencheres\Domain\Model\Item;
use Vd\VdOjvencheres\Domain\Repository\ItemRepository;
use Vd\VdOjvencheres\Mvc\Controller\AbstractController;

use function serialize;

class ItemController extends AbstractController
{
    protected ItemRepository $itemRepository;

    public function injectItemRepository(ItemRepository $itemRepository): void
    {
        $this->itemRepository = $itemRepository;
    }

    public function listAction(ItemDemand $demand = null): void
    {
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $demand = $this->createDemand($demand);
        $items = $this->itemRepository->findDemanded($demand);

        if ($demand === null) {
            $this->initializeFilters($items);
        }

        $this->initializePagination($items);
        $this->view->assignMultiple([
            'categories' => $this->sessionStorage->getSerializedKey('categories'),
            'demand' => $demand,
            'items' => $items,
            'numberOfRecords' => $items->count(),
            'subCategories' => $this->sessionStorage->getSerializedKey('subCategories')
        ]);

        CacheUtility::addCacheTagsForRecords('tx_vdojvencheres', $items);
    }

    public function newsletterAction(): void
    {
        $demand = new ItemDemand();

        if (isset($this->settings['category']) === true && $this->settings['category'] !== '') {
            $category = (int)$this->settings['category'];

            $demand->setCategory($category);

            $this->view->assign('category', $this->itemCategoryRepository->findByUid($category));
        }

        $items = $this->itemRepository->findDemanded($demand);

        $this->view->assign('items', $items);

        CacheUtility::addCacheTagsForRecords('tx_vdojvencheres', $items);
    }

    public function showAction(Item $item = null): void
    {
        if ($item === null) {
            $arguments = $this->getRequest()->getQueryParams();

            if ((bool)$arguments['no_cache'] === true) {
                $uid = (int)$arguments['tx_vdojvencheres_item']['item_preview'];

                if ($uid > 0) {
                    /** @noinspection CallableParameterUseCaseInTypeContextInspection */
                    $item = $this->itemRepository->findByUid($uid, false);
                }
            }
        }

        if ($item === null) {
            $this->pageNotFoundAction();
        }

        $this->view->assign('item', $item);

        CacheUtility::addCacheTagsForPages('tx_vdojvencheres', $this->getStoragePids());
        CacheUtility::addCacheTagsForRecord('tx_vdojvencheres', $item);
    }

    protected function initializeFilters(QueryResultInterface $items): void
    {
        $filters = $this->filterService->buildItemFilters($items);

        $this->sessionStorage->push([
            'categories' => serialize($filters['categories']),
            'subCategories' => serialize($filters['subCategories'])
        ]);
    }
}
