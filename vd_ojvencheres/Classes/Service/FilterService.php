<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdOjvencheres\Service;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

use function array_fill_keys;
use function array_keys;
use function array_unshift;
use function count;
use function strcmp;
use function uasort;

class FilterService
{
    protected LanguageServiceFactory $languageServiceFactory;

    public function __construct(LanguageServiceFactory $languageServiceFactory)
    {
        $this->languageServiceFactory = $languageServiceFactory;
    }

    public function buildItemFilters(QueryResultInterface $items): array
    {
        return $this->build(
            $items,
            [
                'categories' => static fn ($item) => $item->getFirstItemCategory(),
                'subCategories' => static fn ($item) => $item->getFirstItemSubCategory()
            ]
        );
    }

    public function buildSaleFilters(QueryResultInterface $sales): array
    {
        return $this->build(
            $sales,
            [
                'mainItemCategories' => static fn ($sale) => ($item = $sale->getFirstSaleItem())
                    ? $item->getFirstItemCategory()
                    : null,
                'mainOffices' => static fn ($sale) => $sale->getMainOffice(),
                'salesCategories' => static fn ($sale) => $sale->getFirstSaleCategory()
            ]
        );
    }

    protected function addItem(array &$filters, string $type, ?object $item): void
    {
        if ($item === null) {
            return;
        }

        $uid = $item->getUid();

        if (isset($filters[$type][$uid]) === true) {
            return;
        }

        $filters[$type][$uid] = [
            'name' => $item->getName(),
            'uid' => $uid
        ];
    }

    protected function build(QueryResultInterface $items, array $configuration): array
    {
        $filters = array_fill_keys(array_keys($configuration), []);

        foreach ($items as $item) {
            foreach ($configuration as $type => $closure) {
                $this->addItem($filters, $type, $closure($item));
            }
        }

        $keys = array_keys($filters);

        foreach ($keys as $type) {
            $this->sortByName($filters, $type);
            $this->prependDefault($filters, $type);
        }

        return $filters;
    }

    protected function getLanguageService(): LanguageService
    {
        $request = $this->getRequest();
        $language = $request->getAttribute('language') ?? $request->getAttribute('site')->getDefaultLanguage();

        return $this->languageServiceFactory->create($language->getTwoLetterIsoCode());
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }

    protected function prependDefault(array &$filters, string $type): void
    {
        array_unshift(
            $filters[$type],
            [
                'name' => $this->getLanguageService()->sL(
                    'LLL:EXT:vd_ojvencheres/Resources/Private/Language/locallang.xlf:all_'
                    . GeneralUtility::camelCaseToLowerCaseUnderscored($type)
                ),
                'uid' => -1
            ]
        );
    }

    protected function sortByName(array &$filters, string $type): void
    {
        if (count($filters[$type]) === 0) {
            return;
        }

        uasort($filters[$type], static fn ($a, $b) => strcmp($a['name'], $b['name']));
    }
}
