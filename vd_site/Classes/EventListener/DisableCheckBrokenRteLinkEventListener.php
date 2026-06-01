<?php

declare(strict_types=1);

namespace Vd\VdSite\EventListener;

use TYPO3\CMS\Core\Html\Event\BrokenLinkAnalysisEvent;

final class DisableCheckBrokenRteLinkEventListener
{
    public function __invoke(BrokenLinkAnalysisEvent $event): void
    {
    }
}
