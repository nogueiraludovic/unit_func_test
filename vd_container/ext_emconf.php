<?php

$EM_CONF['vd_container'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'plugin',
    'constraints' => [
        'depends' => [
            'container' => '2.3.0-2.3.99',
            'content_defender' => '3.4.0-3.4.99',
            'php' => '7.4.0-7.4.99',
            'typo3' => '10.4.0-10.4.99'
        ]
    ],
    'description' => 'Container extension for vd.ch.',
    'state' => 'stable',
    'title' => 'VD Container',
    'version' => '10.4.0'
];
