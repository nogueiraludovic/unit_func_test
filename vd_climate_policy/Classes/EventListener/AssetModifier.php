<?php

declare(strict_types=1);

namespace Vd\VdClimatePolicy\EventListener;

use Vd\VdFrontend\Event\AfterInitializeActionEvent;

final class AssetModifier
{
    public function __invoke(AfterInitializeActionEvent $event): void
    {
        if ($event->getPage()['uid'] !== 2025500) {
            return;
        }

        $event->getAssetCollector()->addJavaScript(
            'vd-climate-policy',
            'EXT:vd_climate_policy/Resources/Public/JavaScript/vd-climate-policy.min.js'
        );
    }
}
