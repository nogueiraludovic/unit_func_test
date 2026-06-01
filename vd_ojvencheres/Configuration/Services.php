<?php

/** @noinspection PhpInternalEntityUsedInspection */
declare(strict_types=1);

namespace Vd\VdOjvencheres;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\DependencyInjection\PublicServicePass;

return static function (ContainerConfigurator $_, ContainerBuilder $containerBuilder): void {
    $containerBuilder->addCompilerPass(new PublicServicePass('vd_ojvencheres.public'));
    $containerBuilder->registerForAutoconfiguration(Middleware\AjaxFilter::class)->addTag('vd_ojvencheres.public');
    $containerBuilder
        ->registerForAutoconfiguration(DataProcessing\AbstractProcessor::class)
        ->addTag('vd_ojvencheres.public');
    $containerBuilder
        ->registerForAutoconfiguration(FormDataProvider\TcaColumnsOverrides::class)
        ->addTag('vd_ojvencheres.public');
    $containerBuilder->registerForAutoconfiguration(Hooks\DataHandlerHook::class)->addTag('vd_ojvencheres.public');
    $containerBuilder
        ->registerForAutoconfiguration(PageTitle\BreadcrumbPageTitle::class)
        ->addTag('vd_ojvencheres.public');
    $containerBuilder
        ->registerForAutoconfiguration(TCA\Evaluation\AbstractEvaluation::class)
        ->addTag('vd_ojvencheres.public');
};
