<?php

return [
    'columns' => [
        'uri' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkFields' => 'class,params,target'
                        ]
                    ]
                ],
                'max' => 1024,
                'renderType' => 'inputLink',
                'size' => 50,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:link'
        ]
    ],
    'ctrl' => [
        'label' => 'uri',
        'rootLevel' => 1,
        'title' => 'LLL:EXT:vd_site/Resources/Private/Language/locallang_db.xlf:queue_item',
        'typeicon_classes' => [
            'default' => 'content-clock'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    uri
            '
        ]
    ]
];
