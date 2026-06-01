<?php

declare(strict_types=1);

namespace Vd\VdContactService\TCA\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function count;
use function implode;
use function json_encode;
use function sprintf;

use const JSON_THROW_ON_ERROR;

class ServiceContactElement extends AbstractFormElement
{
    protected ConnectionPool $connection;

    public function __construct(NodeFactory $nodeFactory, array $data)
    {
        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class);

        parent::__construct($nodeFactory, $data);
    }

    public function render(): array
    {
        $output[] = $this->getDepartmentDropDownMenu();
        $output[] = $this->getServiceContactDropDownMenu();
        $output[] = $this->getJavaScriptConfiguration();

        $result = $this->initializeResultArray();
        $result['html'] =  implode("\n", $output);
        $result['requireJsModules'][] = ['TYPO3/CMS/VdContactService/ServiceContact' => '
            function(ServiceContact) {
                ServiceContact.initialize();
            }'
        ];

        return $result;
    }

    protected function fetchAllDepartments(): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdcontactservice_domain_model_department');

        $statement = $queryBuilder
            ->select('code', 'uid')
            ->from('tx_vdcontactservice_domain_model_department')
            ->orderBy('code')
            ->execute();

        $records = [];

        while ($rows = $statement->fetchAssociative()) {
            $records[] = $rows;
        }

        return $records;
    }

    protected function fetchAllServiceContactGroupedByDepartment(): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdcontactservice_domain_model_department');

        $query = $queryBuilder
            ->select(
                'tx_vdcontactservice_domain_model_department.code AS department_code',
                'tx_vdcontactservice_domain_model_department.uid AS department_uid',
                'tx_vdcontactservice_domain_model_service.code AS service_code',
                'tx_vdcontactservice_domain_model_service.uid AS service_uid',
                'tx_vdcontactservice_domain_model_servicecontact.name',
                'tx_vdcontactservice_domain_model_servicecontact.title',
                'tx_vdcontactservice_domain_model_servicecontact.uid',
            )
            ->from('tx_vdcontactservice_domain_model_department')
            ->join(
                'tx_vdcontactservice_domain_model_department',
                'tx_vdcontactservice_domain_model_service',
                'tx_vdcontactservice_domain_model_service',
                $queryBuilder->expr()->eq(
                    'tx_vdcontactservice_domain_model_department.uid',
                    $queryBuilder->quoteIdentifier('tx_vdcontactservice_domain_model_service.department')
                )
            )
            ->join(
                'tx_vdcontactservice_domain_model_service',
                'tx_vdcontactservice_domain_model_servicecontact',
                'tx_vdcontactservice_domain_model_servicecontact',
                $queryBuilder->expr()->eq(
                    'tx_vdcontactservice_domain_model_service.uid',
                    $queryBuilder->quoteIdentifier('tx_vdcontactservice_domain_model_servicecontact.service')
                )
            )
            ->orderBy('tx_vdcontactservice_domain_model_service.code')
            ->execute();

        $serviceContacts = [];

        while ($rows = $query->fetchAssociative()) {
            $label = $rows['service_code'];

            if ($rows['name'] !== '') {
                $label .= ' - ' . $rows['name'];
            }

            if ($rows['title'] !== '') {
                $label .= ' - ' . $rows['title'];
            }

            $serviceContacts[$rows['department_uid']][] = [
                'label' => $label,
                'uid' => $rows['uid']
            ];
        }

        return $serviceContacts;
    }

    protected function fetchServiceByUId(int $uid): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdcontactservice_domain_model_service');

        $record = $queryBuilder
            ->select('*')
            ->from('tx_vdcontactservice_domain_model_service')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)))
            ->execute()
            ->fetchAssociative();

        return $record ?: [];
    }

    protected function fetchServiceContactByUid(int $uid): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdcontactservice_domain_model_servicecontact');

        $record = $queryBuilder
            ->select('*')
            ->from('tx_vdcontactservice_domain_model_servicecontact')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)))
            ->execute()
            ->fetchAssociative();

        return $record ?: [];
    }

    protected function getServiceContactUid(): int
    {
        if ($this->data['tableName'] === 'pages') {
            return (int)$this->data['databaseRow']['service_contact'];
        }

        return (int)$this->data['databaseRow']['pi_flexform']['data']['general']['lDEF']['settings.serviceContact']['vDEF'];
    }

    protected function getDepartmentDropDownMenu(): string
    {
        return '
<div class="form-group t3js-formengine-palette-field t3js-formengine-validation-marker">
    <label class="t3js-formengine-label">Département</label>
    <div class="formengine-field-item t3js-formengine-field-item">
        <div class="form-control-wrap">
            <div class="form-wizards-wrap">
                <div class="form-wizards-element">
                    <select class="form-control form-control-adapt" id="control-department" name="">
                        <option value="">Veuillez choisir un départment</option>
                        ' . $this->getDepartmentOptions() . '
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
        ';
    }

    protected function getDepartmentOptions(): string
    {
        $departments = $this->fetchAllDepartments();
        $options = [];
        $selected = $this->getDepartmentUid();

        foreach ($departments as $department) {
            if ($department['code'] === '' || $department['uid'] === '') {
                continue;
            }

            if ($department['uid'] === $selected) {
                $options[] = '<option selected="selected" value="' . $department['uid'] . '">'
                    . $department['code']
                    . '</option>';
            } else {
                $options[] = '<option value="' . $department['uid'] . '">' . $department['code'] . '</option>';
            }
        }

        return implode("\n", $options);
    }

    protected function getDepartmentUid(): int
    {
        $serviceContactUid = $this->getServiceContactUid();

        if ($serviceContactUid === 0) {
            return 0;
        }

        $serviceContact = $this->fetchServiceContactByUid($serviceContactUid);

        if (count($serviceContact) === 0) {
            return 0;
        }

        $service = $this->fetchServiceByUId($serviceContact['service']);

        if (count($service) === 0) {
            return 0;
        }

        return $service['department'];
    }

    protected function getJavaScriptConfiguration(): string
    {
        return sprintf(
            '
<script>
  window.Vaud = window.Vaud || {};
  Vaud.settings = {
    department: "%s",
    serviceContact: "%s"
  };
  Vaud.serviceContactGroupedByDepartment = %s;
</script>
            ',
            $this->getDepartmentUid(),
            $this->getServiceContactUid(),
            json_encode($this->fetchAllServiceContactGroupedByDepartment(), JSON_THROW_ON_ERROR)
        );
    }

    protected function getServiceContactDropDownMenu(): string
    {
        if ($this->data['tableName'] === 'pages') {
            $selectName = 'data[pages][' . $this->data['databaseRow']['uid'] . '][service_contact]';
        } else {
            $selectName = 'data[tt_content][' . $this->data['databaseRow']['uid'] . '][pi_flexform][data][general][lDEF][settings.serviceContact][vDEF]';
        }

        return '
<div class="form-group t3js-formengine-palette-field t3js-formengine-validation-marker" id="container-serviceContact">
    <label class="t3js-formengine-label">Contact</label>
    <div class="formengine-field-item t3js-formengine-field-item">
        <div class="form-control-wrap">
            <div class="form-wizards-wrap">
                <div class="form-wizards-element">
                    <select class="form-control form-control-adapt" id="control-serviceContact" name="' . $selectName . '"></select>
                </div>
            </div>
        </div>
    </div>
</div>
        ';
    }
}
