<?php

declare(strict_types=1);

use Vd\VdOjvencheres\Middleware\AjaxFilter;

return [
    'frontend' => [
        'vd/vd-ojvencheres/ajax-filter' => [
            'after' => [
                'typo3/cms-frontend/prepare-tsfe-rendering'
            ],
            'before' => [
                'typo3/cms-frontend/shortcut-and-mountpoint-redirect'
            ],
            'target' => AjaxFilter::class
        ]
    ]
];
