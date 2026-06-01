<?php

$EM_CONF['vd_ojvencheres'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'plugin',
    'constraints' => [
        'depends' => [
            'news' => '10.0.0-10.0.99',
            'php' => '7.4.0-7.4.99',
            'typo3' => '10.4.0-10.4.99',
            'vd_core' => '10.4.0-10.4.99'
        ],
        'suggests' => [
            'seo' => '10.4.0-10.4.99'
        ]
    ],
    'description' => 'Auction management.',
    'state' => 'stable',
    'title' => 'VD OJV Enchères',
    'version' => '10.4.0'
];
