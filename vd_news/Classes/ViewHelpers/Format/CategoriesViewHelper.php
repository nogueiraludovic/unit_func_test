<?php

declare(strict_types=1);

namespace Vd\VdNews\ViewHelpers\Format;

use GeorgRinger\News\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

use function count;
use function implode;
use function is_array;
use function reset;
use function str_replace;
use function strrpos;
use function substr_replace;

class CategoriesViewHelper extends AbstractTagBasedViewHelper
{
    protected ?CategoryRepository $categoryRepository = null;
    protected $escapeChildren = false;
    protected $tagName = 'a';

    public function initializeArguments(): void
    {
        $this
            ->registerArgument('categories', 'mixed', '', true)
            ->registerArgument('listPid', 'int', '');
    }

    public function injectCategoryRepository(CategoryRepository $categoryRepository): void
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function render(): string
    {
        if (count($this->arguments['categories']) === 0) {
            return '';
        }

        if (is_array($this->arguments['categories']) === true) {
            $this->arguments['categories'] = $this->categoryRepository->findByIdList($this->arguments['categories']);
        }

        $categories = [];
        /** @noinspection NullPointerExceptionInspection, PhpPossiblePolymorphicInvocationInspection */
        $uriBuilder = $this->renderingContext->getControllerContext()->getUriBuilder();

        foreach ($this->arguments['categories'] as $category) {
            $uri = $uriBuilder
                ->reset()
                ->setArguments([
                    'tx_solr' => [
                        'filter' => [
                            'category:' . $category->getUid()
                        ]
                    ]
                ]);

            if (isset($this->arguments['listPid']) === true) {
                $uri->setTargetPageUid((int)$this->arguments['listPid']);
            }

            $title = str_replace('CdC-', '', $category->getTitle());

            $this->tag->addAttribute('href', $uri->buildFrontendUri());
            $this->tag->addAttribute('rel', 'category');
            $this->tag->addAttribute('title', $title);
            $this->tag->forceClosingTag(true);
            $this->tag->setContent($title);

            $categories[] = $this->tag->render();
        }

        if (count($categories) === 1) {
            return reset($categories);
        }

        $categories = implode(', ', $categories);

        return substr_replace(
            $categories,
            ' ' . LocalizationUtility::translate('and', 'VdNews'),
            strrpos($categories, ','),
            1
        );
    }
}
