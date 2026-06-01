<?php

declare(strict_types=1);

namespace Vd\VdSite\Command\Links;

use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

trait FlashMessageTrait
{
    protected function addFlashMessage(int $counter): void
    {
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);

        if ($counter === 0) {
            // @extensionScannerIgnoreLine
            $flashMessageService
                ->getMessageQueueByIdentifier()
                ->addMessage(new FlashMessage('Records are up to date. Good job!', '', AbstractMessage::INFO));
        } else {
            // @extensionScannerIgnoreLine
            $flashMessageService
                ->getMessageQueueByIdentifier()
                ->addMessage(new FlashMessage($counter . ' records updated correctly.'));
        }
    }
}
