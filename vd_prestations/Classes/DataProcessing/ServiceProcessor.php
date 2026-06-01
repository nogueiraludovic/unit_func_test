<?php

declare(strict_types=1);

namespace Vd\VdPrestations\DataProcessing;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

use function count;

class ServiceProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $arguments = $this->getRequest()->getQueryParams();

        if ($arguments['tx_vdprestations_pi4']['prestation'] === null) {
            return $processedData;
        }

        $uid = (int)$arguments['tx_vdprestations_pi4']['prestation'];

        if ($uid === 0) {
            return $processedData;
        }

        $service = $this->getService($uid);

        if (count($service) === 0) {
            return $processedData;
        }

        $processedData['serviceData'] = $service;

        return $processedData;
    }

    protected function getService(int $uid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_vdprestations_domain_model_prestation');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $record = $queryBuilder
            ->select('*')
            ->from('tx_vdprestations_domain_model_prestation')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)))
            ->execute()
            ->fetchAssociative();

        return $record ?: [];
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
