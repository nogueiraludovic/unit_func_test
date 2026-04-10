<?php

declare(strict_types=1);

use a9f\Fractor\Configuration\FractorConfiguration;
use a9f\FractorTypoScript\Configuration\TypoScriptProcessorOption;
use a9f\Typo3Fractor\Set\Typo3LevelSetList;

return FractorConfiguration::configure()
    ->withOptions([
        TypoScriptProcessorOption::ADD_CLOSING_GLOBAL => false,
        TypoScriptProcessorOption::INDENT_CONDITIONS => true,
        TypoScriptProcessorOption::INDENT_SIZE => 2
    ])
    ->withPaths([__DIR__ . '/../..'])
    ->withSets([Typo3LevelSetList::UP_TO_TYPO3_11])
    ->withSkip([__DIR__ . '/../../.Build']);
