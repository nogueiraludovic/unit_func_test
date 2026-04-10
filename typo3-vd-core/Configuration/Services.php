<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdCore;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\DependencyInjection\PublicServicePass;

return static function (ContainerConfigurator $_, ContainerBuilder $containerBuilder): void {
    $containerBuilder->addCompilerPass(new PublicServicePass('vd_core.public'));
    $containerBuilder->registerForAutoconfiguration(DataProcessing\ConstantsProcessor::class)->addTag('vd_core.public');
    $containerBuilder->registerForAutoconfiguration(DataProcessing\PageTitleProcessor::class)->addTag('vd_core.public');
    $containerBuilder->registerForAutoconfiguration(DataProcessing\TcaGroupProcessor::class)->addTag('vd_core.public');
    $containerBuilder->registerForAutoconfiguration(Hooks\BackendAlertHook::class)->addTag('vd_core.public');
    $containerBuilder->registerForAutoconfiguration(Hooks\ButtonsHook::class)->addTag('vd_core.public');
    $containerBuilder->registerForAutoconfiguration(Hooks\PageLayoutControllerHook::class)->addTag('vd_core.public');
};
