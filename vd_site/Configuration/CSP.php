<?php

declare(strict_types=1);

return [
    'base-uri' => [],
    'child-src' => [
        'allow' => [
            'https://*.googletagmanager.com',
            'https://*.twitter.com',
            'https://*.vimeo.com',
            'https://api.vod2.infomaniak.com',
            'https://app.sli.do',
            'https://cdnjs.cloudflare.com',
            'https://dwa.vd.ch',
            'https://e.issuu.com',
            'https://elearn-services.unige.ch',
            'https://embed-assets.wakelet.com',
            'https://embed.wakelet.com',
            'https://jobtic.ch',
            'https://line.do',
            'https://livestream.com',
            'https://m-vaud.prospective.ch',
            'https://player.vod2.infomaniak.com',
            'https://vaud.prospective.ch',
            'https://vod.infomaniak.com',
            'https://www.google.com',
            'https://www.thinglink.com',
            'https://www.vdairdata.ch',
            'https://www.web-vd.ch',
            'https://www.youtube-nocookie.com',
            'https://www.youtube.com'
        ],
        'self' => false
    ],
    'connect-src' => [
        'allow' => [
            'https://*.analytics.google.com',
            'https://*.deeplink.ai',
            'https://*.etat-de-vaud.ch',
            'https://*.g.doubleclick.net',
            'https://*.google-analytics.com',
            'https://*.google.ch',
            'https://*.google.com',
            'https://*.hotjar.com',
            'https://*.hotjar.io',
            'https://*.vd.ch',
            'https://pagead2.googlesyndication.com',
            'https://stats.g.doubleclick.net'
        ],
        'self' => true
    ],
    'default-src' => [
        'self' => true
    ],
    'font-src' => [
        'allow' => [
            'https://*.deeplink.ai',
            'https://*.hotjar.com',
            'https://cdn.jsdelivr.net',
            'https://maxst.icons8.com'
        ],
        'self' => true
    ],
    'form-action' => [
        'allow' => [
            'https://*.etat-de-vaud.ch',
            'https://*.vd.ch'
        ],
        'self' => true
    ],
    'frame-ancestors' => [
        'allow' => [
            'https://*.ddev.site',
            'https://*.etat-de-vaud.ch',
            'https://*.vd.ch'
        ]
    ],
    'frame-src' => [
        'allow' => [
            'https://*.deeplink.ai',
            'https://*.etat-de-vaud.ch',
            'https://*.twitter.com',
            'https://*.vd.ch',
            'https://*.vimeo.com',
            'https://api.vod2.infomaniak.com',
            'https://app.sli.do',
            'https://app.vidcast.io',
            'https://apps.vs.ch',
            'https://defvd.ch',
            'https://e.issuu.com',
            'https://elearn-services.unige.ch',
            'https://embed.wakelet.com',
            'https://google.com',
            'https://jobtic.ch',
            'https://line.do',
            'https://livestream.com',
            'https://m-vaud.prospective.ch',
            'https://map.geo.admin.ch',
            'https://player.vod2.infomaniak.com',
            'https://prezi-nocookies.com',
            'https://thinglink.com',
            'https://tp.srgssr.ch',
            'https://vaud.prospective.ch',
            'https://vd.sphinxonline.ch',
            'https://vimeo.com',
            'https://vod.infomaniak.com',
            'https://web-vd.ch',
            'https://www.google.com',
            'https://www.googletagmanager.com',
            'https://www.openstreetmap.org',
            'https://www.outilcrde.ch',
            'https://www.vdairdata.ch',
            'https://www.youtube-nocookie.com',
            'https://www.youtube.com'
        ],
        'blob' => true
    ],
    'img-src' => [
        'allow' => [
            'https://*.deeplink.ai',
            'https://*.etat-de-vaud.ch',
            'https://*.google-analytics.com',
            'https://*.google.ch',
            'https://*.google.com',
            'https://*.googletagmanager.com',
            'https://*.hotjar.com',
            'https://*.vd.ch',
            'https://46d3f955f1cb.o3n.io',
            'https://articulateusercontent.com',
            'https://googleads.g.doubleclick.net',
            'https://images.freeimages.com',
            'https://png.vector.me',
            'https://stats.g.doubleclick.net',
            'https://upload.wikimedia.org',
            'https://www.asi37.fr',
            'https://www.honcode.ch'
        ],
        'blob' => true,
        'data' => true,
        'self' => true
    ],
    'media-src' => [
        'self' => true
    ],
    'object-src' => [
        'self' => true
    ],
    'plugin-types' => [],
    'report-only' => false,
    'script-src' => [
        'allow' => [
            'https://*.deeplink.ai',
            'https://*.etat-de-vaud.ch',
            'https://*.google-analytics.com',
            'https://*.googletagmanager.com',
            'https://*.hotjar.com',
            'https://*.vd.ch',
            'https://*.vimeo.com',
            'https://cdn.jsdelivr.net',
            'https://cdn.mouseflow.com',
            'https://cdn.thinglink.me',
            'https://cdnjs.cloudflare.com',
            'https://e.issuu.com',
            'https://e.prezicdn.net',
            'https://embed-assets.wakelet.com',
            'https://jwpsrv.com',
            'https://platform.linkedin.com',
            'https://platform.twitter.com',
            'https://widgets.paper.li',
            'https://www.google.com',
            'https://www.googleadservices.com',
            'https://www.gstatic.com',
            'https://www.skypeassets.com',
            'https://www.youtube.com'
        ],
        'self' => true,
        'unsafe-eval' => true,
        'unsafe-inline' => true
    ],
    'style-src' => [
        'allow' => [
            'https://*.deeplink.ai',
            'https://*.hotjar.com',
            'https://cdn.jsdelivr.net',
            'https://cdn.materialdesignicons.com',
            'https://maxst.icons8.com'
        ],
        'self' => true,
        'unsafe-inline' => true
    ],
    'upgrade-insecure-requests' => true
];
