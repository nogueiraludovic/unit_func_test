<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Domain\Model;

class AccessModality extends AbstractEntity
{
    protected string $additionalInformations = '';
    protected string $averageDelay = '';
    protected string $cost = '';
    protected bool $epayment = false;
    protected bool $externalLink = false;
    protected string $hash = '';
    protected string $howto = '';
    protected string $requiredDocuments = '';
    protected string $securityLevel = '';
    protected string $type = '';
    protected array $updateFields = [
        'type',
        'howTo',
        'additionalInformations',
        'averageDelay',
        'cost',
        'requiredDocuments',
        'epayment',
        'securityLevel',
        'externalLink',
        'url',
    ];
    protected string $url = '';

    public function getAdditionalInformations(): string
    {
        return $this->additionalInformations;
    }

    public function getAverageDelay(): string
    {
        return $this->averageDelay;
    }

    public function getCost(): string
    {
        return $this->cost;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getHowto(): string
    {
        return $this->howto;
    }

    public function getRequiredDocuments(): string
    {
        return $this->requiredDocuments;
    }

    public function getSecurityLevel(): string
    {
        return $this->securityLevel;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function isEpayment(): bool
    {
        return $this->epayment;
    }

    public function isExternalLink(): bool
    {
        return $this->externalLink;
    }

    public function setAdditionalInformations(string $additionalInformations): AccessModality
    {
        $this->additionalInformations = $additionalInformations;

        return $this;
    }

    public function setAverageDelay(string $averageDelay): AccessModality
    {
        $this->averageDelay = $averageDelay;

        return $this;
    }

    public function setCost(string $cost): AccessModality
    {
        $this->cost = $cost;

        return $this;
    }

    public function setEpayment(bool $epayment): AccessModality
    {
        $this->epayment = $epayment;

        return $this;
    }

    public function setExternalLink(bool $externalLink): AccessModality
    {
        $this->externalLink = $externalLink;

        return $this;
    }

    public function setHash(string $hash): AccessModality
    {
        $this->hash = $hash;

        return $this;
    }

    public function setHowto(string $howto): AccessModality
    {
        $this->howto = $howto;

        return $this;
    }

    public function setRequiredDocuments(string $requiredDocuments): AccessModality
    {
        $this->requiredDocuments = $requiredDocuments;

        return $this;
    }

    public function setSecurityLevel(string $securityLevel): AccessModality
    {
        $this->securityLevel = $securityLevel;

        return $this;
    }

    public function setType(string $type): AccessModality
    {
        $this->type = $type;

        return $this;
    }

    public function setUrl(string $url): AccessModality
    {
        $this->url = $url;

        return $this;
    }
}
