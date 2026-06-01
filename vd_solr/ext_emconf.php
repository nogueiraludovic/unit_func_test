<?php

$EM_CONF['vd_solr'] = [
    'author' => 'État de Vaud - DGNSI',
    'author_email' => 'support.typo3@vd.ch',
    'category' => 'template',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-7.4.99',
            'solr' => '11.2.0-11.2.99',
            'typo3' => '10.4.0-10.4.99',
            'vd_core' => '10.4.0-10.4.99',
            'vd_news' => '10.4.0-10.4.99'
        ]
    ],
    'description' => 'Solr templates and configuration.',
    'state' => 'stable',
    'title' => 'VD Solr',
    'version' => '10.4.0'
];
