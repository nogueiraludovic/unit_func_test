<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Service;

use ApacheSolrForTypo3\Solr\Domain\Index\Queue\UpdateHandler\GarbageHandler;
use ApacheSolrForTypo3\Solr\IndexQueue\Queue;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdPrestations\Domain\Model\Prestation;

class SolrService
{
    protected GarbageHandler $garbageHandler;
    protected Queue $queue;

    public function __construct()
    {
        $this->garbageHandler = GeneralUtility::makeInstance(GarbageHandler::class);
        $this->queue = GeneralUtility::makeInstance(Queue::class);
    }

    public function remove(Prestation $prestation): void
    {
        $this->garbageHandler->collectGarbage('tx_vdprestations_domain_model_prestation', $prestation->getUid());
    }

    public function update(Prestation $prestation): void
    {
        $this->queue->updateItem('tx_vdprestations_domain_model_prestation', $prestation->getUid());
    }
}
