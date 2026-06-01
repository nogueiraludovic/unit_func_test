<?php

declare(strict_types=1);

namespace Vd\VdPrestations\TCA\Form;

use TYPO3\CMS\Backend\Utility\BackendUtility;

class LabelRenderer
{
    public function forService(array &$parameters): void
    {
        $record = BackendUtility::getRecord($parameters['table'], $parameters['row']['uid'], 'external_id,title');

        $prestationTitle = $record['external_id'] ? '[' . $record['external_id'] . '] ' : '';
        $prestationTitle .= $record['title'];

        $parameters['title'] = $prestationTitle;
    }
}
