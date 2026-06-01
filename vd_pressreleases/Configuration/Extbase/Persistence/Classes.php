<?php

declare(strict_types=1);

use Vd\VdPressreleases\Domain\Model\Department;

return [
    Department::class => [
        'properties' => [
            'code' => [
                'fieldName' => 'code'
            ],
            'label' => [
                'fieldName' => 'label'
            ]
        ],
        'tableName' => 'tx_vddptservices_departments'
    ]
];
