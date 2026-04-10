<?php

declare(strict_types=1);

namespace Vd\VdCore\Hooks;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class BackendAlertHookTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = [
        'core'
    ];

    #[Test]
    public function drawHeaderEnqueuesFlashMessages(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/BackendAlertHookTest.csv');

        (new BackendAlertHook($this->get(ConnectionPool::class)))->drawHeader();

        $messages = $this->get(FlashMessageService::class)->getMessageQueueByIdentifier()->getAllMessages();

        $this->assertCount(2, $messages);
        $this->assertSame('Second title', $messages[0]->getTitle());
        $this->assertSame('Second content', $messages[0]->getMessage());
        $this->assertSame(FlashMessage::WARNING, $messages[0]->getSeverity());
        $this->assertSame('First title', $messages[1]->getTitle());
        $this->assertSame('First content', $messages[1]->getMessage());
    }
}
