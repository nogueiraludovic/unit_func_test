<?php

$EM_CONF['vd_pressreleases'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'plugin',
    'constraints' => [
        'depends' => [
            'news' => '10.0.0-10.0.99',
            'php' => '7.4.0-7.4.99',
            'typo3' => '10.4.0-10.4.99',
            'vd_core' => '10.4.0-10.4.99',
            'web2pdf' => '2.0.0-2.0.99'
        ]
    ],
    'description' => 'Press release management.',
    'state' => 'stable',
    'title' => 'VD Press Releases',
    'version' => '10.4.0'
];
