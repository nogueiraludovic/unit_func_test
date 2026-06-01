<?php

declare(strict_types=1);

namespace Vd\VdSolr\Factory;

use ApacheSolrForTypo3\Solr\Domain\Variants\IdBuilder;
use ApacheSolrForTypo3\Solr\System\Solr\Document\Document;

class DocumentFactory
{
    public static function create(array $externalDocuments): array
    {
        $idBuilder = new IdBuilder();

        foreach ($externalDocuments as $externalDocument) {
            $document = (new Document())
                ->setField('access', 'r:0')
                ->setField('author', $externalDocument->getAuthor())
                ->setField('appKey', 'EXT:solr')
                ->setField('changed', $externalDocument->getChanged()->format('Y-m-d\TH:i:s\Z'))
                ->setField('content', $externalDocument->getContent())
                ->setField(
                    'id',
                    $externalDocument->getSiteHash() . '/' . $externalDocument->getType() . '/' . $externalDocument->getUid()
                )
                ->setField('created', $externalDocument->getCreated()->format('Y-m-d\TH:i:s\Z'))
                ->setField('pid', 0)
                ->setField('site', $externalDocument->getSite())
                ->setField('siteHash', $externalDocument->getSiteHash())
                ->setField('title', $externalDocument->getTitle())
                ->setField('type', $externalDocument->getType())
                ->setField(
                    'variantId',
                    $idBuilder->buildFromTypeAndUid($externalDocument->getType(), $externalDocument->getUid())
                )
                ->setField('uid', $externalDocument->getUid())
                ->setField('url', $externalDocument->getUrl());

            $additionalFields = $externalDocument->getAdditionalFields();

            foreach ($additionalFields as $key => $value) {
                $document->setField($key, $value);
            }

            $documents[] = $document;
        }

        return $documents ?? [];
    }
}
