<?php

$EM_CONF['vd_alertes'] = [
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
    'description' => 'Display alerts in the frontend.',
    'state' => 'stable',
    'title' => 'VD Alertes',
    'version' => '10.4.0'
];
