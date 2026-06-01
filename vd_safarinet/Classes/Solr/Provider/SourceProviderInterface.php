<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Solr\Provider;

interface SourceProviderInterface
{
    public function getDocuments(array $options): array;
}
