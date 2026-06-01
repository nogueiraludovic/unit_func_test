<?php

declare(strict_types=1);

namespace Vd\VdSolr\Hooks;

use ApacheSolrForTypo3\Solr\SubstitutePageIndexer;
use ApacheSolrForTypo3\Solr\System\Solr\Document\Document;

use function strlen;
use function substr;

class ModifyPageTitleHook implements SubstitutePageIndexer
{
    public function getPageDocument(Document $originalPageDocument): Document
    {
        $search = ' | État de Vaud';
        $title = $originalPageDocument->getTitle();

        if (substr($title, -strlen($search)) !== $search) {
            return $originalPageDocument;
        }

        $originalPageDocument->setField('title', substr($title, 0, -strlen($search)));

        return $originalPageDocument;
    }
}
