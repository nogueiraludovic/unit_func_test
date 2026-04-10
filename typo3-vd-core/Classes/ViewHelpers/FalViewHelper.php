<?php

declare(strict_types=1);

namespace Vd\VdCore\ViewHelpers;

/** @noinspection PhpDeprecationInspection */
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function is_array;

final class FalViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    #[CodeCoverageIgnore]
    public function initializeArguments(): void
    {
        $this->registerArgument('as', 'string', '', false, 'items');
        $this->registerArgument('data', 'array', '', true);
        $this->registerArgument('field', 'string', '', false, 'image');
        $this->registerArgument('table', 'string', '', false, 'tt_content');
    }

    public function render(): string
    {
        $variableProvider = $this->renderingContext->getVariableProvider();

        if (is_array($this->arguments['data']) === true && $this->arguments['data']['uid'] > 1) {
            $fileRepository = GeneralUtility::makeInstance(FileRepository::class);

            $items = $fileRepository->findByRelation(
                $this->arguments['table'],
                $this->arguments['field'],
                $this->arguments['data']['uid']
            );

            $localizedId = null;

            if (isset($this->arguments['data']['_LOCALIZED_UID']) === true) {
                $localizedId = $this->arguments['data']['_LOCALIZED_UID'];
            } elseif (isset($this->arguments['data']['_PAGES_OVERLAY_UID']) === true) {
                $localizedId = $this->arguments['data']['_PAGES_OVERLAY_UID'];
            }

            if (
                (
                    isset($GLOBALS['TCA'][$this->arguments['table']]['ctrl']['languageField']) === true
                    && $GLOBALS['TCA'][$this->arguments['table']]['ctrl']['languageField'] !== ''
                    && isset($GLOBALS['TCA'][$this->arguments['table']]['ctrl']['transOrigPointerField']) === true
                    && $GLOBALS['TCA'][$this->arguments['table']]['ctrl']['transOrigPointerField'] !== ''
                )
                && $localizedId !== null
            ) {
                $items = $fileRepository->findByRelation(
                    $this->arguments['table'],
                    $this->arguments['field'],
                    $localizedId
                );
            }
        }

        $variableProvider->add($this->arguments['as'], $items ?? null);
        $content = $this->renderChildren();
        $variableProvider->remove($this->arguments['as']);

        return $content;
    }
}
