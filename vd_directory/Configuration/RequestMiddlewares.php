<?php

declare(strict_types=1);

use Vd\VdDirectory\Middleware\AjaxMiddleware;

return [
    'frontend' => [
        'vd/directory/ajax' => [
            'after' => [
                'typo3/cms-frontend/authentication'
            ],
            'before' => [
                'typo3/cms-frontend/base-redirect-resolver'
            ],
            'target' => AjaxMiddleware::class
        ]
    ]
];
