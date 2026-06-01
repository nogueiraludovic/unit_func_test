<?php

declare(strict_types=1);

namespace Vd\VdApprenticeship\DataProcessing;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

use function count;

class ApprenticeshipProcessor implements DataProcessorInterface
{
    protected ConnectionPool $connection;

    public function __construct(ConnectionPool $connection)
    {
        $this->connection = $connection;
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $arguments = $this->getRequest()->getQueryParams();

        if (isset($arguments['tx_vdfrontend_recorddetail']['record']) === false) {
            return $processedData;
        }

        $uid = (int)$arguments['tx_vdfrontend_recorddetail']['record'];

        if ($uid === 0) {
            return $processedData;
        }

        $address = $this->getAddress($uid);

        if (count($address) === 0) {
            return $processedData;
        }

        $processedData['addressData'] = $address;

        return $processedData;
    }

    protected function getAddress(int $uid): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tt_address');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $record = $queryBuilder
            ->select('*')
            ->from('tt_address')
            ->where(
                $queryBuilder->expr()->eq('parent', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT))
            )
            ->execute()
            ->fetchAssociative();

        return $record ?: [];
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
