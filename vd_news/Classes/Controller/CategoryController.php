<?php

declare(strict_types=1);

namespace Vd\VdNews\Controller;

use GeorgRinger\News\Controller\CategoryController as DefaultCategoryController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CategoryController extends DefaultCategoryController
{
    public function listAction(array $overwriteDemand = null): void
    {
        $this->view->assign(
            'categories',
            $this->categoryRepository->findByIdList(GeneralUtility::intExplode(',', $this->settings['categories']))
        );
    }
}
