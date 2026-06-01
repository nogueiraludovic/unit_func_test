<?php

$EM_CONF['vd_records'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'frontend',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-7.4.99',
            'news' => '10.0.0-10.0.99',
            'tt_address' => '6.1.0-6.1.99',
            'typo3' => '10.4.0-10.4.99',
            'vd_core' => '10.4.0-10.4.99',
            'vd_frontend' => '10.4.0-10.4.99',
            'vd_municipalities' => '10.4.0-10.4.99'
        ]
    ],
    'description' => 'Extension to add records and extend existing tables.',
    'state' => 'stable',
    'title' => 'VD Records',
    'version' => '10.4.0'
];
