<?php

declare(strict_types=1);

use In2code\Powermail\Domain\Model\Field as DefaultField;
use In2code\Powermail\Domain\Model\Form as DefaultForm;
use In2code\Powermail\Domain\Model\Page as DefaultPage;
use Vd\VdPowermail\Domain\Model\Field;
use Vd\VdPowermail\Domain\Model\Form;
use Vd\VdPowermail\Domain\Model\Page;

return [
    DefaultField::class => [
        'subclasses' => [
            Field::class
        ]
    ],
    DefaultForm::class => [
        'subclasses' => [
            Form::class
        ]
    ],
    DefaultPage::class => [
        'subclasses' => [
            Page::class
        ]
    ],
    Field::class => [
        'tableName' => 'tx_powermail_domain_model_field'
    ],
    Form::class => [
        'tableName' => 'tx_powermail_domain_model_form'
    ],
    Page::class => [
        'tableName' => 'tx_powermail_domain_model_page'
    ]
];
