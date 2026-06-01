<?php

$EM_CONF['vd_contact_service'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'plugin',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-7.4.99',
            'powermail' => '8.5.0-8.5.99',
            'typo3' => '10.4.0-10.4.99',
            'vd_core' => '10.4.0-10.4.99',
            'vd_municipalities' => '10.4.0-10.4.99'
        ]
    ],
    'description' => 'Add service selector to pages.',
    'state' => 'stable',
    'title' => 'VD Contact Service',
    'version' => '10.4.0'
];
