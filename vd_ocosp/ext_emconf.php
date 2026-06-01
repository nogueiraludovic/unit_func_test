<?php

$EM_CONF['vd_ocosp'] = [
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
    'description' => 'OCOSP frontend.',
    'state' => 'stable',
    'title' => 'VD OCOSP',
    'version' => '10.4.0'
];
