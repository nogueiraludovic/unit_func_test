<?php

declare(strict_types=1);

namespace Vd\VdCore\DataProcessing;

use PHPUnit\Framework\Attributes\Test;
use ReflectionMethod;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class ConstantsProcessorTest extends FunctionalTestCase
{
    #[Test]
    public function processResolvesConstants(): void
    {
        $tsfe = $this->getAccessibleMock(TypoScriptFrontendController::class, null, [], '', false);
        $templateService = new TemplateService();
        $templateService->flatSetup = [
            'plugin.tx_ext.' => '',
            'plugin.tx_ext.value' => '123',
            'plugin.tx_ext.sub' => '{plugin.tx_ext.value}'
        ];
        $tsfe->tmpl = $templateService;
        $GLOBALS['TSFE'] = $tsfe;

        $cObj = new ContentObjectRenderer();
        $cObj->start([]);

        $result = (new ConstantsProcessor(new TypoScriptParser(), new TypoScriptService()))->process(
            $cObj,
            [],
            [
                'key' => 'plugin.tx_ext',
                'as' => 'constants'
            ],
            []
        );

        $this->assertSame(
            [
                'value' => '123',
                'sub' => '123'
            ],
            $result['constants']
        );
    }

    #[Test]
    public function processReturnsEarlyWhenKeyIsEmpty(): void
    {
        $cObj = new ContentObjectRenderer();
        $cObj->start([]);

        $processor = new ConstantsProcessor(new TypoScriptParser(), new TypoScriptService());

        $result = $processor->process(
            $cObj,
            [],
            [
                'key' => ''
            ],
            ['existing' => 'value']
        );

        $this->assertSame(['existing' => 'value'], $result);
    }

    #[Test]
    public function getFlatConstantsSkipsNonMatchingKeys(): void
    {
        $tsfe = $this->getAccessibleMock(TypoScriptFrontendController::class, null, [], '', false);

        $templateService = new TemplateService();
        $templateService->flatSetup = [
            'plugin.tx_ext.value' => '123'
        ];
        $tsfe->tmpl = $templateService;
        $GLOBALS['TSFE'] = $tsfe;

        $processor = new ConstantsProcessor(new TypoScriptParser(), new TypoScriptService());

        $method = new ReflectionMethod($processor, 'getFlatConstants');
        $method->setAccessible(true);

        $this->assertSame('', $method->invoke($processor, 'nonexistent.'));
    }

    #[Test]
    public function getFlatSetupGeneratesConfigWhenFlatSetupIsEmpty(): void
    {
        $tsfe = $this->getAccessibleMock(TypoScriptFrontendController::class, null, [], '', false);

        $templateService = $this->getMockBuilder(TemplateService::class)
            ->onlyMethods(['generateConfig'])
            ->getMock();

        $templateService->flatSetup = [];
        $templateService->expects($this->once())->method('generateConfig');

        $tsfe->tmpl = $templateService;
        $GLOBALS['TSFE'] = $tsfe;

        $processor = new ConstantsProcessor(new TypoScriptParser(), new TypoScriptService());

        $method = new ReflectionMethod($processor, 'getFlatSetup');
        $method->setAccessible(true);

        $method->invoke($processor);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['TSFE']);

        parent::tearDown();
    }
}
