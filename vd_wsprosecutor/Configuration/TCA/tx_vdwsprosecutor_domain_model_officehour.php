<?php

return [
    'columns' => [
        'api_response' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'readOnly' => true,
                'rows' => 20,
                'type' => 'text',
                'wrap' => 'off'
            ],
            'label' => 'LLL:EXT:vd_wsprosecutor/Resources/Private/Language/locallang_db.xlf:api_response'
        ],
        'end_shift' => [
            'config' => [
                'default' => 0,
                'eval' => 'datetime,int,required',
                'readOnly' => true,
                'renderType' => 'inputDateTime',
                'type' => 'input'
            ],
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:vd_wsprosecutor/Resources/Private/Language/locallang_db.xlf:end_shift'
        ],
        'end_shift_date' => [
            'config' => [
                'default' => '',
                'type' => 'passthrough'
            ]
        ],
        'end_shift_date_hour' => [
            'config' => [
                'default' => '',
                'type' => 'passthrough'
            ]
        ],
        'end_shift_day' => [
            'config' => [
                'default' => '',
                'type' => 'passthrough'
            ]
        ],
        'end_shift_hour' => [
            'config' => [
                'default' => '',
                'type' => 'passthrough'
            ]
        ],
        'event' => [
            'config' => [
                'default' => '',
                'type' => 'passthrough'
            ]
        ],
        'external_id' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_wsprosecutor/Resources/Private/Language/locallang_db.xlf:external_id'
        ],
        'magistrate_first_name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.first_name'
        ],
        'magistrate_id' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_wsprosecutor/Resources/Private/Language/locallang_db.xlf:external_id'
        ],
        'magistrate_last_name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.last_name'
        ],
        'magistrate_name' => [
            'config' => [
                'default' => '',
                'type' => 'passthrough'
            ]
        ],
        'modification_date' => [
            'config' => [
                'default' => 0,
                'eval' => 'datetime,int,required',
                'readOnly' => true,
                'renderType' => 'inputDateTime',
                'type' => 'input'
            ],
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.timestamp'
        ],
        'office' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_wsprosecutor/Resources/Private/Language/locallang_db.xlf:office'
        ],
        'start_shift' => [
            'config' => [
                'default' => 0,
                'eval' => 'datetime,int,required',
                'readOnly' => true,
                'renderType' => 'inputDateTime',
                'type' => 'input'
            ],
            'l10n_display' => 'defaultAsReadonly',
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:vd_wsprosecutor/Resources/Private/Language/locallang_db.xlf:start_shift'
        ],
        'start_shift_date' => [
            'config' => [
                'default' => '',
                'type' => 'passthrough'
            ]
        ],
        'start_shift_date_hour' => [
            'config' => [
                'default' => '',
                'type' => 'passthrough'
            ]
        ],
        'start_shift_day' => [
            'config' => [
                'default' => '',
                'type' => 'passthrough'
            ]
        ],
        'start_shift_hour' => [
            'config' => [
                'default' => '',
                'type' => 'passthrough'
            ]
        ],
        'substitute_first_name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.first_name'
        ],
        'substitute_id' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:vd_wsprosecutor/Resources/Private/Language/locallang_db.xlf:external_id'
        ],
        'substitute_last_name' => [
            'config' => [
                'default' => '',
                'eval' => 'trim,required',
                'max' => 255,
                'readOnly' => true,
                'type' => 'input'
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.last_name'
        ],
        'substitute_name' => [
            'config' => [
                'default' => '',
                'type' => 'passthrough'
            ]
        ]
    ],
    'ctrl' => [
        'default_sortby' => 'start_shift',
        'enablecolumns' => [
            'endtime' => 'end_shift'
        ],
        'label' => 'external_id',
        'label_alt' => 'magistrate_name,substitute_name',
        'label_alt_force' => true,
        'rootLevel' => 1,
        'searchFields' => 'external_id,magistrate_id,magistrate_first_name,magistrate_last_name,office,substitute_id,substitute_first_name,substitute_last_name',
        'title' => 'LLL:EXT:vd_wsprosecutor/Resources/Private/Language/locallang_db.xlf:prosecutors_office_hour',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other'
        ]
    ],
    'palettes' => [
        'shift' => [
            'showitem' => 'start_shift,end_shift'
        ],
        'magistrate' => [
            'showitem' => 'magistrate_id,--linebreak--,magistrate_first_name,magistrate_last_name'
        ],
        'substitute' => [
            'showitem' => 'substitute_id,--linebreak--,substitute_first_name,substitute_last_name'
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;shift,
                    office,
                    --palette--;LLL:EXT:vd_wsprosecutor/Resources/Private/Language/locallang_db.xlf:magistrate;magistrate,
                    --palette--;LLL:EXT:vd_wsprosecutor/Resources/Private/Language/locallang_db.xlf:substitute;substitute,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    external_id,
                    modification_date,
                    api_response
            '
        ]
    ]
];
