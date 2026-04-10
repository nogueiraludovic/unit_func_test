<?php

declare(strict_types=1);

use Vd\VdWebservice\Middleware\ApiMiddleware;

return [
    'frontend' => [
        'vd/webservice/api' => [
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
