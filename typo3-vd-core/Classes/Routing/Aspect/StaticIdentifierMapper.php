<?php

declare(strict_types=1);

namespace Vd\VdCore\Routing\Aspect;

/** @noinspection PhpDeprecationInspection */
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use TYPO3\CMS\Core\Routing\Aspect\StaticMappableAspectInterface;
use TYPO3\CMS\Core\Site\SiteLanguageAwareTrait;

#[CodeCoverageIgnore]
class StaticIdentifierMapper implements StaticMappableAspectInterface
{
    use SiteLanguageAwareTrait;

    public function generate(string $value): ?string
    {
        return $value;
    }

    public function resolve(string $value): ?string
    {
        return $value;
    }
}
