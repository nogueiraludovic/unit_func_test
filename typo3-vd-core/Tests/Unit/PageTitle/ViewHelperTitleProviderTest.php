<?php

declare(strict_types=1);

namespace Vd\VdCore\Tests\Unit\PageTitle;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vd\VdCore\PageTitle\ViewHelperTitleProvider;

final class ViewHelperTitleProviderTest extends TestCase
{
    protected ViewHelperTitleProvider $provider;

    #[Test]
    public function setTitleAddsSuffixIfMissing(): void
    {
        $this->provider->setTitle('Accueil');
        $this->assertSame('Accueil | État de Vaud', $this->provider->getTitle());
    }

    #[Test]
    public function setTitleDoesNotDuplicateSuffix(): void
    {
        $this->provider->setTitle('Accueil | État de Vaud');
        $this->assertSame('Accueil | État de Vaud', $this->provider->getTitle());
    }

    #[Test]
    public function setTitleDoesNotOverwriteExistingTitle(): void
    {
        $this->provider->setTitle('Accueil');
        $this->provider->setTitle('Something else');
        $this->assertSame('Accueil | État de Vaud', $this->provider->getTitle());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = new ViewHelperTitleProvider();
    }
}
