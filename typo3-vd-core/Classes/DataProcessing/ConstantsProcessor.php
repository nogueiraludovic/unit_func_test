<?php

declare(strict_types=1);

namespace Vd\VdCore\DataProcessing;

use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

use function count;
use function ltrim;
use function preg_match_all;
use function str_replace;
use function str_starts_with;
use function strlen;
use function substr;
use function trim;

use const LF;

readonly class ConstantsProcessor implements DataProcessorInterface
{
    public function __construct(
        protected TypoScriptParser $typoScriptParser,
        protected TypoScriptService $typoScriptService
    ) {
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $key = $cObj->stdWrapValue('key', $processorConfiguration);

        if ($key === '') {
            return $processedData;
        }

        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration) ?: 'constants';

        $processedData[$targetVariableName] = $this->typoScriptService
            ->convertTypoScriptArrayToPlainArray($this->getTypoScriptParser($key . '.')->setup);

        return $processedData;
    }

    protected function getFlatConstants(string $key): string
    {
        $flatSetup = $this->getFlatSetup();
        $flatConstants = '';

        foreach ($flatSetup as $constantPath => $constantValue) {
            if (str_starts_with($constantPath, $key) === false) {
                continue;
            }

            $flatConstants .= substr($constantPath, strlen($key))
                . ' = '
                . $this->resolveSubConstants($constantValue, $flatSetup) . LF;
        }

        return trim($flatConstants);
    }

    protected function getFlatSetup(): array
    {
        $templateService = $this->getFrontendController()->tmpl;

        if (count($templateService->flatSetup) === 0) {
            $templateService->generateConfig();
        }

        return $templateService->flatSetup;
    }

    protected function getFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    protected function getTypoScriptParser(string $key): TypoScriptParser
    {
        $this->typoScriptParser->parse($this->getFlatConstants($key));

        return $this->typoScriptParser;
    }

    protected function resolveSubConstants(string $constant, array $flatConstants): string
    {
        $hasSubConstant = preg_match_all('/{\K[^}]*(?=})/m', $constant, $subConstants);

        if ((bool)$hasSubConstant === false) {
            return $constant;
        }

        foreach ($subConstants[0] as $subConstant) {
            $constant = str_replace('{' . $subConstant . '}', $flatConstants[ltrim($subConstant, '$')], $constant);
        }

        return $constant;
    }
}
