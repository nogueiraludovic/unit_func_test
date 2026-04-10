<?php

declare(strict_types=1);

use Vd\VdCore\Middleware\ContentSecurityPolicyHeader;
use Vd\VdCore\Middleware\GeneratePermalink;
use Vd\VdCore\Middleware\PermalinkRedirect;

return [
    'frontend' => [
        'vd/vd-core/content-security-policy-header' => [
            'after' => [
                'typo3/cms-frontend/content-length-headers'
            ],
            'before' => [
                'vd/vd-core/generate-permalink'
            ],
            'target' => ContentSecurityPolicyHeader::class
        ],
        'vd/vd-core/generate-permalink' => [
            'after' => [
                'typo3/cms-frontend/content-length-headers'
            ],
            'before' => [
                'typo3/cms-frontend/output-compression'
            ],
            'target' => GeneratePermalink::class
        ],
        'vd/vd-core/permalink-redirect' => [
            'after' => [
                'typo3/cms-frontend/site'
            ],
            'before' => [
                'typo3/cms-frontend/base-redirect-resolver',
                'typo3/cms-frontend/static-route-resolver'
            ],
            'target' => PermalinkRedirect::class
        ]
    ]
];
