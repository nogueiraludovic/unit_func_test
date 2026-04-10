<?php

declare(strict_types=1);

namespace Vd\VdCore\Utility;

use TYPO3\CMS\Core\Utility\ArrayUtility;

class TcaUtility
{
    public static function getFieldConfiguration(
        string $field,
        array $customConfiguration = [],
        string $table = ''
    ): array {
        $configuration = match ($field) {
            'description' => [
                'config' => [
                    'default' => '',
                    'eval' => 'trim',
                    'type' => 'text'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.description'
            ],
            'disable', 'hidden' => [
                'config' => [
                    'default' => false,
                    'items' => [
                        [
                            0 => '',
                            'invertStateDisplay' => true
                        ]
                    ],
                    'renderType' => 'checkboxToggle',
                    'type' => 'check'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible'
            ],
            'editlock' => [
                'config' => [
                    'default' => false,
                    'renderType' => 'checkboxToggle',
                    'type' => 'check'
                ],
                'displayCond' => 'HIDE_FOR_NON_ADMINS',
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:editlock'
            ],
            'endtime' => [
                'config' => [
                    'default' => 0,
                    'eval' => 'datetime,int',
                    'renderType' => 'inputDateTime',
                    'type' => 'input'
                ],
                'exclude' => true,
                'l10n_display' => 'defaultAsReadonly',
                'l10n_mode' => 'exclude',
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime'
            ],
            'fe_group' => [
                'config' => [
                    'default' => '',
                    'exclusiveKeys' => '-1,-2',
                    'foreign_table' => 'fe_groups',
                    'items' => [
                        [
                            'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hide_at_login',
                            -1
                        ],
                        [
                            'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.any_login',
                            -2
                        ],
                        [
                            'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.usergroups',
                            '--div--'
                        ]
                    ],
                    'maxitems' => 20,
                    'renderType' => 'selectMultipleSideBySide',
                    'size' => 5,
                    'type' => 'select'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.fe_group'
            ],
            'l10n_diffsource' => [
                'config' => [
                    'default' => '',
                    'type' => 'passthrough'
                ]
            ],
            'l10n_parent' => [
                'config' => [
                    'default' => 0,
                    'disableNoMatchingValueElement' => true,
                    'foreign_table' => $table,
                    'foreign_table_where' => '{#' . $table . '}.{#pid}=###CURRENT_PID### AND {#' . $table . '}.{#sys_language_uid} IN (-1,0)',
                    'items' => [
                        [
                            '',
                            0
                        ]
                    ],
                    'renderType' => 'selectSingle',
                    'type' => 'select'
                ],
                'displayCond' => 'FIELD:sys_language_uid:>:0',
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent'
            ],
            'l10n_source' => [
                'config' => [
                    'default' => 0,
                    'type' => 'passthrough'
                ]
            ],
            'starttime' => [
                'config' => [
                    'default' => 0,
                    'eval' => 'datetime,int',
                    'renderType' => 'inputDateTime',
                    'type' => 'input'
                ],
                'exclude' => true,
                'l10n_display' => 'defaultAsReadonly',
                'l10n_mode' => 'exclude',
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime'
            ],
            'sys_language_uid' => [
                'config' => [
                    'type' => 'language'
                ],
                'exclude' => true,
                'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language'
            ],
            default => [],
        };

        if ($customConfiguration !== []) {
            ArrayUtility::mergeRecursiveWithOverrule($configuration, $customConfiguration);
        }

        return $configuration;
    }
}
