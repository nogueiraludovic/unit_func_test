<?php

$EM_CONF['vd_directory'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'plugin',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-7.4.99',
            'typo3' => '10.4.0-10.4.99',
            'vd_frontend' => '10.4.0-10.4.99'
        ]
    ],
    'description' => 'Directory of addresses.',
    'state' => 'stable',
    'title' => 'VD Directory',
    'version' => '10.4.0'
];
