<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdWsprosecutor;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\DependencyInjection\PublicServicePass;

return static function (ContainerConfigurator $_, ContainerBuilder $containerBuilder): void {
    $containerBuilder->addCompilerPass(new PublicServicePass('vd_wsprosecutor.public'));
    $containerBuilder
        ->registerForAutoconfiguration(Controller\EsbController::class)
        ->addTag('vd_wsprosecutor.public');
    $containerBuilder
        ->registerForAutoconfiguration(Domain\Repository\OfficeHourRepository::class)
        ->addTag('vd_wsprosecutor.public');
    $containerBuilder
        ->registerForAutoconfiguration(Service\CacheService::class)
        ->addTag('vd_wsprosecutor.public');
};
