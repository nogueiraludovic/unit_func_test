<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Controller;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class RoutingController extends ActionController
{
    public function redirectAction(): void
    {
        $queryParams = $this->getRequest()->getQueryParams();
        $prestationUid = $this->getPrestationUid((int)$queryParams['prestation']);

        if ($prestationUid === 0) {
            $this->redirectToUri('/');
        }

        $this->redirectToUri(
            $this->uriBuilder
                ->reset()
                ->setArguments([
                    'tx_vdprestations_pi4[action]' => 'show',
                    'tx_vdprestations_pi4[controller]' => 'Prestation',
                    'tx_vdprestations_pi4[prestation]' => $prestationUid
                ])
                ->setCreateAbsoluteUri(true)
                ->setTargetPageUid((int)$this->settings['singlePid'])
                ->build()
        );
    }

    protected function getPrestationUid(int $externalId): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        return (int)$queryBuilder
            ->select('uid')
            ->from('tx_vdprestations_domain_model_prestation')
            ->where(
                $queryBuilder->expr()->eq(
                    'external_id',
                    $queryBuilder->createNamedParameter($externalId, Connection::PARAM_INT)
                )
            )
            ->execute()
            ->fetchOne();
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
