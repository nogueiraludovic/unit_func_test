<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\PageTitle;

use Psr\Http\Message\ServerRequestInterface;
use Vd\VdOjvencheres\Domain\Repository\ItemRepository;

use function str_replace;

class BreadcrumbPageTitle
{
    protected ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function render(string $_, array $parameters): string
    {
        $arguments = $this->getRequest()->getQueryParams();
        $uid = (int)$arguments['tx_vdojvencheres_item']['item'];

        $item = $this->itemRepository->findByUid($uid);

        if ($item === null) {
            return '';
        }

        $title = $item->getTitleWithCategory();

        if ($parameters['wrap'] !== null) {
            $title = str_replace('|', $title, $parameters['wrap']);
        }

        return $title;
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
