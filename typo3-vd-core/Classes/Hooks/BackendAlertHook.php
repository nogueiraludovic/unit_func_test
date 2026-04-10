<?php

declare(strict_types=1);

namespace Vd\VdCore\Hooks;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function strip_tags;

readonly class BackendAlertHook
{
    public function __construct(private ConnectionPool $connection)
    {
    }

    public function drawHeader(): void
    {
        $statement = $this->connection
            ->getQueryBuilderForTable('sys_news')
            ->select('content', 'title')
            ->from('sys_news')
            ->orderBy('crdate', 'DESC')
            ->executeQuery();

        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);

        while ($rows = $statement->fetchAssociative()) {
            $flashMessageService
                ->getMessageQueueByIdentifier()
                ->enqueue(
                    GeneralUtility::makeInstance(
                        FlashMessage::class,
                        strip_tags((string)$rows['content']),
                        strip_tags((string)$rows['title']),
                        FlashMessage::WARNING
                    )
                );
        }
    }
}
