<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use Vd\VdOjvencheres\Domain\Repository\SaleRepository;

class SaleProcessor extends AbstractProcessor
{
    protected SaleRepository $saleRepository;

    public function __construct(SaleRepository $saleRepository)
    {
        $this->saleRepository = $saleRepository;
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $arguments = $this->getRequest()->getQueryParams();

        if (
            $arguments['tx_vdojvencheres_sale']['sale'] === null
            && $arguments['tx_vdojvencheres_sale']['sale_preview'] === null
        ) {
            return $processedData;
        }

        $uid = (int)($arguments['tx_vdojvencheres_sale']['sale'] ?: $arguments['tx_vdojvencheres_sale']['sale_preview']);

        if ($uid === 0) {
            return $processedData;
        }

        $sale = $this->saleRepository->findByUid($uid, false);

        if ($sale === null) {
            return $processedData;
        }

        $processedData['saleData'] = $sale;

        return $processedData;
    }
}
