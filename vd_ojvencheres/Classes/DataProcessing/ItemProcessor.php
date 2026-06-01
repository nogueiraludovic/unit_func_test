<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Vd\VdOjvencheres\Domain\Repository\ItemRepository;

class ItemProcessor extends AbstractProcessor
{
    protected ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $arguments = $this->getRequest()->getQueryParams();

        if (
            $arguments['tx_vdojvencheres_item']['item'] === null
            && $arguments['tx_vdojvencheres_item']['item_preview'] === null
        ) {
            return $processedData;
        }

        $uid = (int)($arguments['tx_vdojvencheres_item']['item'] ?: $arguments['tx_vdojvencheres_item']['item_preview']);

        if ($uid === 0) {
            return $processedData;
        }

        $item = $this->itemRepository->findByUid($uid, false);

        if ($item === null) {
            return $processedData;
        }

        $processedData['itemData'] = $item;

        return $processedData;
    }
}
