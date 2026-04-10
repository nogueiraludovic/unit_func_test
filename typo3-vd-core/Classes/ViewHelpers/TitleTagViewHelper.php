<?php

declare(strict_types=1);

namespace Vd\VdCore\ViewHelpers;

/** @noinspection PhpDeprecationInspection */
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Vd\VdCore\PageTitle\ViewHelperTitleProvider;

use function trim;

final class TitleTagViewHelper extends AbstractViewHelper
{
    #[CodeCoverageIgnore]
    public function getContentArgumentName(): string
    {
        return 'value';
    }

    #[CodeCoverageIgnore]
    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', '');
    }

    public function render(): void
    {
        $content = trim((string)$this->renderChildren());

        if ($content === '') {
            return;
        }

        GeneralUtility::makeInstance(ViewHelperTitleProvider::class)->setTitle($content);
    }
}
