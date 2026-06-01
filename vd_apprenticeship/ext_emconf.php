<?php

$EM_CONF['vd_apprenticeship'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'plugin',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-7.4.99',
            'tt_address' => '6.1.0-6.1.99',
            'typo3' => '10.4.0-10.4.99',
            'vd_core' => '10.4.0-10.4.99',
            'vd_frontend' => '10.4.0-10.4.99'
        ]
    ],
    'description' => 'Manage list and detail views of apprenticeships.',
    'state' => 'stable',
    'title' => 'VD Apprenticeship',
    'version' => '10.4.0'
];
