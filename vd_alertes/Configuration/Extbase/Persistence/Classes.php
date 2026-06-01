<?php

declare(strict_types=1);

use Vd\VdAlertes\Domain\Model\HomeAlert;
use Vd\VdAlertes\Domain\Model\ServiceAlert;

return [
    HomeAlert::class => [
        'tableName' => 'tx_vdalertes_domain_model_home_alert'
    ],
    ServiceAlert::class => [
        'tableName' => 'tx_vdalertes_domain_model_service_alert'
    ]
];
