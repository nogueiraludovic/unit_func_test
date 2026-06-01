<?php

declare(strict_types=1);

namespace Vd\VdSolr\Tests\Unit\Factory;

use DateTime;

class ExternalDocumentStubTest
{
    public function getAdditionalFields(): array
    {
        return [
            'customField1' => 'value1',
            'customField2' => 'value2',
        ];
    }

    public function getAuthor(): string
    {
        return 'John Doe';
    }

    public function getChanged(): DateTime
    {
        return new DateTime('2023-01-01T12:00:00Z');
    }

    public function getContent(): string
    {
        return 'Sample content';
    }

    public function getCreated(): DateTime
    {
        return new DateTime('2022-12-31T08:00:00Z');
    }

    public function getSite(): string
    {
        return 'example.com';
    }

    public function getSiteHash(): string
    {
        return 'sitehash123';
    }

    public function getTitle(): string
    {
        return 'Sample Title';
    }

    public function getType(): string
    {
        return 'article';
    }

    public function getUid(): int
    {
        return 42;
    }

    public function getUrl(): string
    {
        return 'https://example.com/article/42';
    }
}
