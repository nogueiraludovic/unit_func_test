<?php

$EM_CONF['vd_safarinet'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'plugin',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-7.4.99',
            'seo' => '10.4.0-10.4.99',
            'typo3' => '10.4.0-10.4.99',
            'vd_core' => '10.4.0-10.4.99',
            'vd_solr' => '10.4.0-10.4.99'
        ],
        'suggests' => [
            'seo' => '10.4.0-10.4.99'
        ]
    ],
    'description' => 'Manage "Conseil d\État" and "Grand Conseil" meetings and information.',
    'state' => 'stable',
    'title' => 'VD Safarinet',
    'version' => '10.4.0'
];
