<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class SmallAds extends AbstractEntity
{
    protected string $cat = '';
    protected string $cat2 = '';
    protected string $comment = '';
    protected string $content = '';
    protected int $crdateexternal = 0;
    protected bool $displayemail = false;
    protected string $email = '';
    protected ?FileReference $image = null;
    protected bool $iscommercial = false;
    protected string $phone = '';
    protected bool $reviewed = false;
    protected string $slug = '';
    protected string $title = '';
    protected string $user = '';
    protected int $vdexternaluid = 0;

    public function getCat(): string
    {
        return $this->cat;
    }

    public function getCat2(): string
    {
        return $this->cat2;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCrdateexternal(): int
    {
        return $this->crdateexternal;
    }

    public function getDisplayemail(): bool
    {
        return $this->displayemail;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getImage(): ?FileReference
    {
        return $this->image;
    }

    public function getIscommercial(): bool
    {
        return $this->iscommercial;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getReviewed(): bool
    {
        return $this->reviewed;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getVdexternaluid(): int
    {
        return $this->vdexternaluid;
    }

    public function setCat(string $cat): Smallads
    {
        $this->cat = $cat;

        return $this;
    }

    public function setCat2(string $cat2): Smallads
    {
        $this->cat2 = $cat2;

        return $this;
    }

    public function setComment(string $comment): Smallads
    {
        $this->comment = $comment;

        return $this;
    }

    public function setContent(string $content): Smallads
    {
        $this->content = $content;

        return $this;
    }

    public function setCrdateexternal(int $crdateexternal): Smallads
    {
        $this->crdateexternal = $crdateexternal;

        return $this;
    }

    public function setDisplayemail(bool $displayemail): Smallads
    {
        $this->displayemail = $displayemail;

        return $this;
    }

    public function setEmail(string $email): Smallads
    {
        $this->email = $email;

        return $this;
    }

    public function setImage(FileReference $image): Smallads
    {
        $this->image = $image;

        return $this;
    }

    public function setIscommercial(bool $iscommercial): Smallads
    {
        $this->iscommercial = $iscommercial;

        return $this;
    }

    public function setPhone(string $phone): Smallads
    {
        $this->phone = $phone;

        return $this;
    }

    public function setReviewed(bool $reviewed): Smallads
    {
        $this->reviewed = $reviewed;

        return $this;
    }

    public function setSlug(string $slug): Smallads
    {
        $this->slug = $slug;

        return $this;
    }

    public function setTitle(string $title): Smallads
    {
        $this->title = $title;

        return $this;
    }

    public function setUser(string $user): Smallads
    {
        $this->user = $user;

        return $this;
    }

    public function setVdexternaluid(int $vdexternaluid): Smallads
    {
        $this->vdexternaluid = $vdexternaluid;

        return $this;
    }
}
