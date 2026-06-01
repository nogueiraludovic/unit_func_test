<?php

declare(strict_types=1);

namespace Vd\VdSolr\Tests\Unit\Hooks;

use ApacheSolrForTypo3\Solr\System\Solr\Document\Document;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdSolr\Hooks\ModifyPageTitleHook;

class ModifyPageTitleHookTest extends UnitTestCase
{
    protected ModifyPageTitleHook $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new ModifyPageTitleHook();
    }

    public function testTitleIsUnchangedIfSuffixIsNotPresent(): void
    {
        $document = new Document();
        $document->setField('title', 'Welcome to Vaud');

        $this->assertSame('Welcome to Vaud', $this->subject->getPageDocument($document)->getTitle());
    }

    public function testTitleIsModifiedIfSuffixIsPresent(): void
    {
        $document = new Document();
        $document->setField('title', 'Welcome to Vaud | État de Vaud');

        $this->assertSame('Welcome to Vaud', $this->subject->getPageDocument($document)->getTitle());
    }
}
