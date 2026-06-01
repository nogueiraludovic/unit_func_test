<?php

declare(strict_types=1);

namespace Vd\VdPrestations\EventListener;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Event\Persistence\EntityPersistedEvent;
use Vd\VdPrestations\Domain\Model\Prestation;

class EntityPersistedEventListener
{
    public function __invoke(EntityPersistedEvent $event): void
    {
        $object = $event->getObject();

        if (($object instanceof Prestation) === false) {
            return;
        }

        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_vdprestations_domain_model_prestation')
            ->update(
                'tx_vdprestations_domain_model_prestation',
                [
                    'uid' => (int)$object->getExternalId()
                ],
                [
                    'uid' => (int)$object->getUid()
                ]
            );
    }
}
