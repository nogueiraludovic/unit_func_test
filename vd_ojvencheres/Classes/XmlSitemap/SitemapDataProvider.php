<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\XmlSitemap;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Seo\XmlSitemap\RecordsXmlSitemapDataProvider;
use Vd\VdOjvencheres\Domain\Repository\ItemRepository;
use Vd\VdOjvencheres\Domain\Repository\SaleRepository;

class SitemapDataProvider extends RecordsXmlSitemapDataProvider
{
    public function generateItems(): void
    {
        switch ($this->config['table']) {
            case 'tx_vdojvencheres_domain_model_item':
                $items = GeneralUtility::makeInstance(ItemRepository::class)->findDemanded();
                break;
            case 'tx_vdojvencheres_domain_model_sale':
                $items = GeneralUtility::makeInstance(SaleRepository::class)->findDemanded();
                break;
            default:
                return;
        }

        foreach ($items as $item) {
            $this->items[] = [
                'data' => [
                    'uid' => $item->getUid()
                ],
                'lastMod' => $item->getTstamp(),
                'priority' => 0.5
            ];
        }
    }
}
