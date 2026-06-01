<?php

declare(strict_types=1);

namespace Vd\VdContactService\TCA\Form;

use TYPO3\CMS\Backend\Utility\BackendUtility;

use function implode;

class LabelRenderer
{
    public function forServiceContact(array &$parameters): void
    {
        $serviceContact = BackendUtility::getRecord(
            $parameters['table'],
            $parameters['row']['uid'],
            'name,service,title'
        );

        $service = BackendUtility::getRecord(
            'tx_vdcontactservice_domain_model_service',
            $serviceContact['service'],
            'code,department'
        );

        $department = BackendUtility::getRecord(
            'tx_vdcontactservice_domain_model_department',
            $service['department'],
            'name'
        );

        $title = [];

        if ($service['code'] !== '') {
            $title[] = $service['code'];
        }

        if ($department['name'] !== '') {
            $title[] = $department['name'];
        }

        if ($serviceContact['name'] !== '') {
            $title[] = $serviceContact['name'];
        }

        if ($serviceContact['title'] !== '') {
            $title[] = $serviceContact['title'];
        }

        $parameters['title'] = implode(' - ', $title);
    }
}
