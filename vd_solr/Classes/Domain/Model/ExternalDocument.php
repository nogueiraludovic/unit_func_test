<?php

declare(strict_types=1);

namespace Vd\VdSolr\Domain\Model;

use DateTime;

use function getenv;
use function parse_url;
use function sha1;
use function str_replace;
use function strip_tags;

use const PHP_URL_HOST;

class ExternalDocument
{
    protected array $additionalFields = [];
    protected string $author = '';
    protected DateTime $changed;
    protected string $content = '';
    protected DateTime $created;
    protected string $site = '';
    protected string $siteHash = '';
    protected string $title = '';
    protected string $type = '';
    protected int $uid = 0;
    protected string $url = '';

    public function getAdditionalFields(): array
    {
        return $this->additionalFields;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getChanged(): DateTime
    {
        return $this->changed;
    }

    public function getContent(): string
    {
        // @extensionScannerIgnoreLine
        return $this->content;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function getSite(): string
    {
        return $this->site;
    }

    public function getSiteHash(): string
    {
        return $this->siteHash;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUid(): int
    {
        return $this->uid;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setAdditionalFields(array $additionalFields): ExternalDocument
    {
        $this->additionalFields = $additionalFields;

        return $this;
    }

    public function setAuthor(string $author): ExternalDocument
    {
        $this->author = $author;

        return $this;
    }

    public function setChanged(DateTime $changed): ExternalDocument
    {
        $this->changed = $changed;

        return $this;
    }

    public function setContent(string $content): ExternalDocument
    {
        // @extensionScannerIgnoreLine
        $this->content = strip_tags($content);

        return $this;
    }

    public function setCreated(DateTime $created): ExternalDocument
    {
        $this->created = $created;

        return $this;
    }

    public function setSite(string $site): ExternalDocument
    {
        $this->site = str_replace(
            parse_url((string)getenv('DGNSI_BACKEND_URL'), PHP_URL_HOST),
            parse_url((string)getenv('DGNSI_PUBLIC_URL'), PHP_URL_HOST),
            $site
        );

        return $this;
    }

    public function setSiteHash(): ExternalDocument
    {
        $this->siteHash = sha1($this->site . $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] . 'tx_solr');

        return $this;
    }

    public function setTitle(string $title): ExternalDocument
    {
        $this->title = $title;

        return $this;
    }

    public function setType(string $type): ExternalDocument
    {
        $this->type = $type;

        return $this;
    }

    public function setUid(int $uid): ExternalDocument
    {
        $this->uid = $uid;

        return $this;
    }

    public function setUrl(string $url): ExternalDocument
    {
        $this->url = str_replace((string)getenv('DGNSI_BACKEND_PATH'), '', $url);

        return $this;
    }
}
