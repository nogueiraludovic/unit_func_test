<?php

declare(strict_types=1);

use GeorgRinger\News\Domain\Model\FileReference as DefaultFileReference;
use GeorgRinger\News\Domain\Model\News as DefaultNews;
use Vd\VdNews\Domain\Model\FileReference;
use Vd\VdNews\Domain\Model\News;

return [
    DefaultFileReference::class => [
        'subclasses' => [
            FileReference::class
        ]
    ],
    DefaultNews::class => [
        'subclasses' => [
            3 => News::class
        ]
    ],
    FileReference::class => [
        'tableName' => 'sys_file_reference'
    ],
    News::class => [
        'recordType' => 3,
        'tableName' => 'tx_news_domain_model_news'
    ]
];
