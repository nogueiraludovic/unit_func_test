<?php

namespace Vd\VdOcosp\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Vd\VdOcosp\Utility\TextParserUtility;

/**
 * Wrapper around Ocosp_Wiki class
 *
 * = Examples =
 *
 * <code title="Simple Loop">
 * <vd:wiki parseText="true" parseLinks="false">
 * - foo
 * - bar
 * </vd:wiki>
 * </code>
 * <output>
 * <ul>
 * <li>foo</li>
 * <li>bar</li>
 * </ul>
 * </output>
 */
class WikiViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    protected $escapeChildren = false;

    /**
     * Sorts lists of items according to given criteria and loop on them for rendering.
     *
     * @return string Rendered string
     * @api
     */
    public function render()
    {
        $parseText = $this->arguments['parseText'];
        $parseLinks = $this->arguments['parseLinks'];
        $parseText = (bool)$parseText;
        $parseLinks = (bool)$parseLinks;
        $textParser = GeneralUtility::makeInstance(TextParserUtility::class);
        // Render the content first, to apply transformations to it
        $content = $this->renderChildren();
        if ($parseLinks) {
            $content = $textParser->parseForUrls($content);
        }
        if ($parseText) {
            $content = $textParser->renderLists(trim($content));
        }
        return nl2br(trim($content));
    }

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('parseText', 'bool', 'TRUE if text must be parsed (for bullet list structures)', false);
        $this->registerArgument('parseLinks', 'bool', 'TRUE if text must be parsed for links', false);
    }
}
