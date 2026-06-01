<?php

declare(strict_types=1);

namespace Vd\VdClimatePolicy\TCA\Form;

use TYPO3\CMS\Backend\Utility\BackendUtility;

class LabelRenderer
{
    public function forPecc(array &$parameters): void
    {
        $pecc = BackendUtility::getRecord(
            $parameters['table'],
            $parameters['row']['uid'],
            'name,number'
        );

        $parameters['title'] = $pecc['number'] . ' | ' . $pecc['name'];
    }
}
