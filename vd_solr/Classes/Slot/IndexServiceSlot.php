<?php

declare(strict_types=1);

namespace Vd\VdSolr\Slot;

use ApacheSolrForTypo3\Solr\IndexQueue\Item;
use ApacheSolrForTypo3\Solr\Task\IndexQueueWorkerTask;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IndexServiceSlot
{
    public function beforeIndexItemSlot(
        Item $itemToIndex,
        IndexQueueWorkerTask $contextTask = null,
        string $indexRunId = ''
    ): array {
        if ($itemToIndex->getType() !== 'tx_news_domain_model_news') {
            return [$itemToIndex, $contextTask, $indexRunId];
        }

        $record = $itemToIndex->getRecord();

        if ((int)$record['type'] !== 3) {
            return [$itemToIndex, $contextTask, $indexRunId];
        }

        $selectedArticle = (int)$record['selected_article'];

        if ($selectedArticle === 0) {
            return [$itemToIndex, $contextTask, $indexRunId];
        }

        $alias = $this->getAlias($selectedArticle);

        $record['alias'] = $alias['uid'];
        $record['aliasurl'] = 't3://record?identifier=news&uid=' . $alias['uid'];
        $record['author'] = $alias['author'];
        $record['author_email'] = $alias['author_email'];
        $record['bodytext'] = $alias['bodytext'];
        $record['categories'] = $alias['categories'];
        $record['datetime'] = $alias['datetime'];
        $record['keywords'] = $alias['keywords'];
        $record['path_segment'] = $alias['path_segment'];
        $record['related_files'] = $alias['related_files'];
        $record['tags'] = $alias['tags'];
        $record['teaser'] = $alias['teaser'];
        $record['title'] = $alias['title'];

        $itemToIndex->setRecord($record);

        return [$itemToIndex, $contextTask, $indexRunId];
    }

    protected function getAlias(int $uid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_news_domain_model_news');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $record = $queryBuilder
            ->select('*')
            ->from('tx_news_domain_model_news')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)))
            ->execute()
            ->fetchAssociative();

        return $record ?: [];
    }
}
