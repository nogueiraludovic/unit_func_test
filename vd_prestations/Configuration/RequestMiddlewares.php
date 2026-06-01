<?php

declare(strict_types=1);

use Vd\VdPrestations\Middleware\ApiMiddleware;

return [
    'frontend' => [
        'vd/prestations/api' => [
            'after' => [
                'typo3/cms-frontend/authentication'
            ],
            'before' => [
                'typo3/cms-frontend/base-redirect-resolver'
            ],
            'target' => ApiMiddleware::class
        ]
    ]
];
