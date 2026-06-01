<?php

$EM_CONF['vd_powermail'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-7.4.99',
            'powermail' => '8.5.0-8.5.99',
            'typo3' => '10.4.0-10.4.99'
        ]
    ],
    'description' => 'Default configuration for EXT:powermail.',
    'state' => 'stable',
    'title' => 'VD Powermail',
    'version' => '10.4.0'
];
