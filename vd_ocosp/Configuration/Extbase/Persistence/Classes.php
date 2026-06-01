<?php

declare(strict_types=1);

use Vd\VdOcosp\Domain\Model\Adresse;
use Vd\VdOcosp\Domain\Model\Diplome;
use Vd\VdOcosp\Domain\Model\Dmde;
use Vd\VdOcosp\Domain\Model\Domaine;
use Vd\VdOcosp\Domain\Model\Infometiers\Classe;
use Vd\VdOcosp\Domain\Model\Infometiers\Domaine as ImDomaine;
use Vd\VdOcosp\Domain\Model\Inscription;
use Vd\VdOcosp\Domain\Model\Interet;
use Vd\VdOcosp\Domain\Model\Profession;

return [
    Adresse::class => [
        'tableName' => 'tx_vdocosp_adresses'
    ],
    Classe::class => [
        'tableName' => 'tx_vdocosp_classes'
    ],
    Diplome::class => [
        'tableName' => 'tx_vdocosp_diplomes'
    ],
    Dmde::class => [
        'tableName' => 'tx_vdocosp_dmde'
    ],
    Domaine::class => [
        'tableName' => 'tx_vdocosp_domaines'
    ],
    ImDomaine::class => [
        'tableName' => 'tx_vdocosp_im_domaines'
    ],
    Inscription::class => [
        'tableName' => 'tx_vdocosp_inscriptions'
    ],
    Interet::class => [
        'tableName' => 'tx_vdocosp_interets'
    ],
    Profession::class => [
        'properties' => [
            'tstamp' => [
                'fieldName' => 'tstamp'
            ]
        ],
        'tableName' => 'tx_vdocosp_professions'
    ]
];
