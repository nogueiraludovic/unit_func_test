<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdRecords;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\DependencyInjection\PublicServicePass;

return static function (ContainerConfigurator $_, ContainerBuilder $containerBuilder): void {
    $containerBuilder->addCompilerPass(new PublicServicePass('vd_records.public'));
    $containerBuilder->registerForAutoconfiguration(Hooks\DataHandlerHook::class)->addTag('vd_records.public');
    $containerBuilder->registerForAutoconfiguration(Updates\FullAddressUpdate::class)->addTag('vd_records.public');
};
