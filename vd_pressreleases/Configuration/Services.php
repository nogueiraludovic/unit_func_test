<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdPressreleases;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\DependencyInjection\PublicServicePass;

return static function (ContainerConfigurator $_, ContainerBuilder $containerBuilder): void {
    $containerBuilder->addCompilerPass(new PublicServicePass('vd_pressreleases.public'));
    $containerBuilder
        ->registerForAutoconfiguration(DataProcessing\PressReleaseProcessor::class)
        ->addTag('vd_pressreleases.public');
};
