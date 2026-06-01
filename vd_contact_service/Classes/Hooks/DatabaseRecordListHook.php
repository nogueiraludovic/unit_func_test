<?php

declare(strict_types=1);

namespace Vd\VdContactService\Hooks;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;

class DatabaseRecordListHook
{
    public function makeSearchStringConstraints(
        QueryBuilder $queryBuilder,
        array $constraints,
        string $searchString,
        string $table,
        int $currentPid
    ): array {
        if ($table !== 'tx_vdcontactservice_domain_model_servicecontact') {
            return $constraints;
        }

        $searchFields = [
            'tx_vdcontactservice_domain_model_department.name',
            'tx_vdcontactservice_domain_model_service.code',
            'tx_vdcontactservice_domain_model_servicecontact.name'
        ];

        foreach ($searchFields as $field) {
            $constraints[] = $queryBuilder->expr()->andX(
                $queryBuilder->expr()->comparison(
                    'LOWER(' . $queryBuilder->castFieldToTextType($field) . ')',
                    'LIKE',
                    'LOWER(' . $queryBuilder->quote('%' . $queryBuilder->escapeLikeWildcards($searchString) . '%') . ')'
                )
            );
        }

        return $constraints;
    }

    public function modifyQuery(
        array $parameters,
        string $table,
        int $pageId,
        array $additionalConstraints,
        array $fieldList,
        QueryBuilder $queryBuilder
    ): void {
        if ($table !== 'tx_vdcontactservice_domain_model_servicecontact') {
            return;
        }

        $queryBuilder
            ->select('tx_vdcontactservice_domain_model_servicecontact.*')
            ->leftJoin(
                'tx_vdcontactservice_domain_model_servicecontact',
                'tx_vdcontactservice_domain_model_service',
                'tx_vdcontactservice_domain_model_service',
                'tx_vdcontactservice_domain_model_service.uid=tx_vdcontactservice_domain_model_servicecontact.service'
            )
            ->leftJoin(
                'tx_vdcontactservice_domain_model_service',
                'tx_vdcontactservice_domain_model_department',
                'tx_vdcontactservice_domain_model_department',
                'tx_vdcontactservice_domain_model_service.department=tx_vdcontactservice_domain_model_department.uid'
            );
    }
}
