<?php

$EM_CONF['vd_wsprosecutor'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'plugin',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-7.4.99',
            'typo3' => '10.4.0-10.4.99',
            'typoscript_rendering' => '2.4.0-2.4.99',
            'vd_core' => '10.4.0-10.4.99',
        ]
    ],
    'description' => 'Manage current and future prosecutor\'s office hours.',
    'state' => 'stable',
    'title' => 'VD Web Service Prosecutor',
    'version' => '10.4.0'
];
