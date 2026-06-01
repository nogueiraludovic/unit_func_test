<?php

declare(strict_types=1);

namespace Vd\VdSite\Updates;

use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

use function is_array;

class MigrateNewsletterUpdate implements UpgradeWizardInterface
{
    protected const CONTENT_ELEMENTS_TO_DELETE = [1201024, 2099524];
    protected const CONTENT_ELEMENTS_TO_UPDATE = [
        1183816 => ['listId' => 16, 'unsubscribe' => true],
        1201111 => ['listId' => 95],
        1201112 => ['listId' => 95, 'unsubscribe' => true],
        1201113 => ['listId' => 96],
        1201114 => ['listId' => 96, 'unsubscribe' => true],
        1201115 => ['listId' => 97],
        1201116 => ['listId' => 97, 'unsubscribe' => true],
        1201117 => ['listId' => 98],
        1201118 => ['listId' => 98, 'unsubscribe' => true],
        1201119 => ['listId' => 99],
        1201120 => ['listId' => 99, 'unsubscribe' => true],
        1201188 => [
            'fields' => [
                [
                    'label' => 'Nom Complet',
                    'oemproName' => 'CustomField15',
                    'property' => 'fullName'
                ]
            ],
            'listId' => 103
        ],
        1201190 => [
            'fields' => [
                [
                    'label' => 'Nom Complet',
                    'oemproName' => 'CustomField15',
                    'property' => 'fullName'
                ]
            ],
            'listId' => 102
        ],
        1225646 => [
            'fields' => [
                [
                    'label' => 'Nom Complet',
                    'oemproName' => 'CustomField16',
                    'property' => 'fullName'
                ],
                [
                    'label' => 'Société',
                    'oemproName' => 'CustomField17',
                    'property' => 'company'
                ]
            ],
            'listId' => 125
        ],
        2057436 => ['listId' => 208],
        2057883 => ['listId' => 264],
        2057899 => ['listId' => 183],
        2062589 => ['listId' => 396],
        2072804 => ['listId' => 579],
        2082528 => [
            'header' => 'S\'inscrire à la lettre d\'information de Statistique Vaud',
            'header_layout' => 2,
            'listId' => 934
        ],
        2083889 => ['listId' => 1008],
        2089561 => ['listId' => 1168],
        2096939 => ['listId' => 1312],
        2099315 => ['listId' => 916],
        2099525 => [
            'header' => 'Inscription aux communiqués de presse',
            'header_layout' => 2,
            'listId' => 1364
        ],
        2119720 => ['listId' => 1581]
    ];

    protected ConnectionPool $connection;
    protected FlexFormTools $flexFormTools;

    public function __construct(ConnectionPool $connection, FlexFormTools $flexFormTools)
    {
        $this->connection = $connection;
        $this->flexFormTools = $flexFormTools;
    }

    public function executeUpdate(): bool
    {
        $connection = $this->connection->getConnectionForTable('tt_content');

        foreach (self::CONTENT_ELEMENTS_TO_DELETE as $uid) {
            $connection->delete('tt_content', ['uid' => $uid]);
        }

        foreach (self::CONTENT_ELEMENTS_TO_UPDATE as $uid => $data) {
            $connection->update(
                'tt_content',
                [
                    'bodytext' => null,
                    'CType' => 'list',
                    'header' => $data['header'] ?? '',
                    'header_layout' => $data['header_layout'] ?? 100,
                    'list_type' => 'vdnewsletter_subscription',
                    'pi_flexform' => $this->flexFormTools->flexArray2Xml($this->buildFlexForm($data), true)
                ],
                [
                    'uid' => $uid
                ]
            );
        }

        return true;
    }

    public function getDescription(): string
    {
        return 'Migrate all HTML newsletter forms to plugin.';
    }

    public function getIdentifier(): string
    {
        return 'vdSiteMigrateNewsletter';
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    public function getTitle(): string
    {
        return 'vd_site: Migrate newsletter';
    }

    public function updateNecessary(): bool
    {
        return true;
    }

    protected function buildFieldSectionElements(array $fields): array
    {
        $elements = [];
        $index = 1;

        foreach ($fields as $field) {
            $elements[$index] = [
                'configuration' => [
                    'el' => [
                        'label' => [
                            'vDEF' => (string)($field['label'] ?? ''),
                        ],
                        'oemproName' => [
                            'vDEF' => (string)($field['oemproName'] ?? ''),
                        ],
                        'property' => [
                            'vDEF' => (string)($field['property'] ?? '')
                        ]
                    ]
                ]
            ];

            ++$index;
        }

        return $elements;
    }

    protected function buildFlexForm(array $data): array
    {
        $flexForm = [
            'data' => [
                'general' => [
                    'lDEF' => [
                        'settings.apiUrl' => [
                            'vDEF' => 'https://emailing.vd.ch/api.php'
                        ],
                        'settings.command' => [
                            'vDEF' => (bool)($data['unsubscribe'] ?? false) === true
                                ? 'Subscriber.Unsubscribe'
                                : 'Subscriber.Subscribe'
                        ],
                        'settings.listId' => [
                            'vDEF' => (string)($data['listId'] ?? '')
                        ]
                    ]
                ]
            ]
        ];

        if (isset($data['fields']) === true && is_array($data['fields']) === true) {
            $flexForm['data']['fields'] = [
                'lDEF' => [
                    'settings.fields' => [
                        'el' => $this->buildFieldSectionElements($data['fields'])
                    ]
                ]
            ];
        }

        return $flexForm;
    }
}
