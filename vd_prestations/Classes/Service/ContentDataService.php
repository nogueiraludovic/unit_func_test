<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Service;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdPrestations\Utility\FlexFormUtility;

class ContentDataService
{
    protected array $contentElement = [];

    public function getPid(): int
    {
        return (int)$this->contentElement['pid'];
    }

    public function getSettings(): array
    {
        $flexForm = GeneralUtility::xml2array($this->contentElement['pi_flexform']);
        $normalizedFlexForm = GeneralUtility::makeInstance(FlexFormUtility::class)->normalizeFlexForm($flexForm);

        return $normalizedFlexForm['settings'] ?? [];
    }

    public function setContentElement(int $uid): ContentDataService
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $record = $queryBuilder
            ->select('pid', 'pi_flexform', 'uid')
            ->from('tt_content')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)))
            ->execute()
            ->fetchAssociative();

        $this->contentElement = $record ?: [];

        return $this;
    }
}
