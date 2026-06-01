<?php

$EM_CONF['vd_prestations'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'plugin',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-7.4.99',
            'typo3' => '10.4.0-10.4.99',
            'vd_core' => '10.4.0-10.4.99'
        ]
    ],
    'description' => 'Synchronize VD prestaKit and FE display.',
    'state' => 'stable',
    'title' => 'VD Prestations',
    'version' => '10.4.0'
];
