<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdSite;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\DependencyInjection\PublicServicePass;

return static function (ContainerConfigurator $_, ContainerBuilder $containerBuilder): void {
    $containerBuilder->addCompilerPass(new PublicServicePass('vd_site.public'));
    $containerBuilder->registerForAutoconfiguration(Updates\AddTurnstileCaptchaUpdate::class)->addTag('vd_site.public');
    $containerBuilder->registerForAutoconfiguration(Updates\ExecuteSqlQueriesUpdate::class)->addTag('vd_site.public');
    $containerBuilder->registerForAutoconfiguration(Updates\MigrateNewsletterUpdate::class)->addTag('vd_site.public');
};
