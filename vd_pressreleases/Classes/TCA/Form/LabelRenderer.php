<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\TCA\Form;

use TYPO3\CMS\Backend\Utility\BackendUtility;

use function count;
use function implode;
use function is_array;

class LabelRenderer
{
    public function forContacts(array &$parameters): void
    {
        $contact = $this->getRecord($parameters['table'], (int)$parameters['row']['uid']);

        if (is_array($contact) === false) {
            $parameters['title'] = '';

            return;
        }

        $parts = [];

        if ($contact['department'] !== '') {
            $parts[] = $contact['department'];
        }

        if ($contact['service'] !== '') {
            $parts[] = $contact['service'];
        }

        if ($contact['function'] !== '') {
            $parts[] = $contact['function'];
        }

        $parameters['title'] = $contact['name'] . (count($parts) > 0 ? ' (' . implode(', ', $parts) . ')' : '');
    }

    protected function getRecord(string $table, int $uid): ?array
    {
        return BackendUtility::getRecord($table, $uid);
    }
}
