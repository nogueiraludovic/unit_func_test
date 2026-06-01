<?php

declare(strict_types=1);

namespace Vd\VdSolr\Tests\Unit\Factory;

use ApacheSolrForTypo3\Solr\System\Solr\Document\Document;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdSolr\Factory\DocumentFactory;

class DocumentFactoryTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] = 'testEncryptionKey1234567890';
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['modifyVariantId'] = [];
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['indexQueue']['types']['article']['additionalFields'] = [];

        $GLOBALS['TSFE'] = $this->createMock(TypoScriptFrontendController::class);
    }

    public function testCreateReturnsCorrectDocument(): void
    {
        $this->resetSingletonInstances = true;

        $externalDocument = new ExternalDocumentStubTest();

        $documents = DocumentFactory::create([$externalDocument]);

        $this->assertCount(1, $documents);
        $document = $documents[0];

        $this->assertInstanceOf(Document::class, $document);
        $this->assertSame('John Doe', $document->getAuthor());
        $this->assertSame('Sample content', $document->getContent());
        $this->assertSame('sitehash123/article/42', $document->getId());
        $this->assertSame('value1', $document->getCustomField1());
        $this->assertSame('value2', $document->getCustomField2());
    }
}
