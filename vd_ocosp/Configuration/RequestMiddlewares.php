<?php

declare(strict_types=1);

use Vd\VdOcosp\Middleware\AjaxMiddleware;

return [
    'frontend' => [
        'vd/ocosp/ajax' => [
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
