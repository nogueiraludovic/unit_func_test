<?php

$EM_CONF['vd_publications_cg'] = [
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
    'description' => 'Publications for the "CG" service.',
    'state' => 'stable',
    'title' => 'VD Publications CG',
    'version' => '10.4.0'
];
