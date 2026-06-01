<?php

declare(strict_types=1);

use Vd\VdMunicipalities\Domain\Model\District;
use Vd\VdMunicipalities\Domain\Model\Municipality;

return [
    District::class => [
        'tableName' => 'tx_vdmunicipalities_districts'
    ],
    Municipality::class => [
        'tableName' => 'tx_vdmunicipalities_municipalities'
    ]
];
