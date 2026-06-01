<?php

$EM_CONF['vd_site'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'min' => '2.1.0-2.1.99',
            'news' => '10.0.0-10.0.99',
            'php' => '7.4.0-7.4.99',
            'seo' => '10.4.0-10.4.99',
            'tt_address' => '6.1.0-6.1.99',
            'typo3' => '10.4.0-10.4.99',
            'vd_core' => '10.4.0-10.4.99',
            'vd_prestations' => '10.4.0-10.4.99'
        ]
    ],
    'description' => 'Main configuration for vd.ch.',
    'state' => 'stable',
    'title' => 'VD Site',
    'version' => '10.4.0'
];
