<?php

declare(strict_types=1);

namespace Vd\VdPressreleases\Domain\Model;

use DateInterval;
use DateTimeInterface;
use DateTimeZone;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

use function array_filter;
use function implode;
use function str_replace;

class Contact extends AbstractEntity
{
    protected string $department = '';
    protected string $email = '';
    protected string $function = '';
    protected string $name = '';
    protected string $phone = '';
    protected string $service = '';
    protected int $sourceId = 0;

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFunction(): string
    {
        return $this->function;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getService(): string
    {
        return $this->service;
    }

    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    public function render(PressRelease $pressRelease): string
    {
        if ($this->email !== '') {
            $frontendController = $this->getFrontendController();

            if (($frontendController instanceof TypoScriptFrontendController) === true) {
                $email = $frontendController->cObj->typoLink(
                    '',
                    [
                        'parameter' => $this->email
                    ]
                );
            } else {
                $email = '<a href="mailto:' . $this->email . '" id="contact-email-to">' . $this->email . '</a>';
            }
        }

        if ($this->phone !== '' && $this->showPhone($pressRelease) === true) {
            $phone = '<a class="phone-link" href="tel:' . str_replace(' ', '', $this->phone) . '" id="phone-link-' . $this->uid . '" rel="nofollow">'
                . $this->phone
                . '</a>';
        }

        return implode(
            ', ',
            array_filter([
                $this->department,
                $this->name,
                $this->function,
                $this->service,
                $email ?? '',
                $phone ?? ''
            ])
        );
    }

    public function setDepartment(string $department): Contact
    {
        $this->department = $department;

        return $this;
    }

    public function setEmail(string $email): Contact
    {
        $this->email = $email;

        return $this;
    }

    public function setFunction(string $function): Contact
    {
        $this->function = $function;

        return $this;
    }

    public function setName(string $name): Contact
    {
        $this->name = $name;

        return $this;
    }

    public function setPhone(string $phone): Contact
    {
        $this->phone = $phone;

        return $this;
    }

    public function setService(string $service): Contact
    {
        $this->service = $service;

        return $this;
    }

    public function setSourceId(int $sourceId): Contact
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    public function showPhone(PressRelease $pressRelease): bool
    {
        if ($pressRelease->hasAnonymize() === false) {
            return true;
        }

        $anonymizeTime = $pressRelease->getAnonymizeTime();
        $context = GeneralUtility::makeInstance(Context::class);

        if (($anonymizeTime instanceof DateTimeInterface) === true) {
            $anonymizeTime->setTimezone(new DateTimeZone('UTC'));

            return $anonymizeTime >= $context->getPropertyFromAspect('date', 'full');
        }

        $dateTime = $pressRelease->getDateTime();

        if (($dateTime instanceof DateTimeInterface) === false) {
            return true;
        }

        $dateTime->setTimezone(new DateTimeZone('UTC'));

        return $context->getPropertyFromAspect('date', 'full') <= (clone $dateTime)->add(new DateInterval('P15D'));
    }

    protected function getFrontendController(): ?TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
