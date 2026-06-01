<?php

declare(strict_types=1);

namespace Vd\VdSolr\XClasses;

use ApacheSolrForTypo3\Solr\System\Service\ConfigurationService;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

use function count;
use function is_array;

class ConfigurationServiceXClass extends ConfigurationService
{
    protected function getFilterFromFlexForm(array $flexFormConfiguration): array
    {
        $filters = ObjectAccess::getPropertyPath($flexFormConfiguration, 'search.query.filter');

        if (is_array($filters) === false || count($filters) === 0) {
            return [];
        }

        $filterConfiguration = [];

        foreach ($filters as $filter) {
            $filterConfiguration[] =  $filter['field']['field'] . ':' . $filter['field']['value'];
        }

        return $filterConfiguration;
    }
}
