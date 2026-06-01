<?php

namespace Vd\VdOcosp\Utility;

use TYPO3\CMS\Core\SingletonInterface;

class TextParserUtility implements SingletonInterface
{
    /**
     * Creates a bullet list from a text comprised of lines marked with "*" or "-".
     *
     * @param string $text
     * @return string
     */
    public function renderLists($text = '')
    {
        $lines = explode(chr(10), $text);

        $parsedString = '';

        $isInList = false;
        $listItems = [];

        foreach ($lines as $line) {
            $line = trim($line);
            // The line starts with a list marker
            if ((strpos($line, '*') === 0) || (strpos($line, '-') === 0)) {
                $isInList = true;
                $trimmedLine = trim(substr($line, 1));
                // Keep the line only if not empty after trimming
                if ($trimmedLine !== '') {
                    $listItems[] = $trimmedLine;
                }
            } else {
                // The line does not start with a list marker => the current list is finished and must be rendered
                if ($isInList) {
                    // Display the list
                    $parsedString .= $this->renderBulletList($listItems);
                    // Reset list variables
                    $isInList = false;
                    $listItems = [];
                }
                // Render the current line
                $parsedString .= $line . '<br/>';
            }
        }
        // If there's a current, final list, render it
        if ($isInList) {
            $parsedString .= $this->renderBulletList($listItems);
        }

        return $parsedString;
    }

    /**
     * Assembles an unordered list from an array of items.
     *
     * @param array $items
     * @return string
     */
    public function renderBulletList($items)
    {
        $list = '';
        if (count($items) > 0) {
            $list .= '<ul>';
            foreach ($items as $item) {
                $list .= '<li>' . $item . '</li>';
            }
            $list .= '</ul>';
        }
        return $list;
    }

    /**
     * Parses a string for URLs in order to make links out of them,
     * but avoiding doing so inside existing <a> tags.
     *
     * @param string $text
     * @return string
     */
    public function parseForUrls($text)
    {
        // Find the start position of all <a> tags
        $matches = [];
        $result = preg_match_all('/<a.*?>/', $text, $matches, PREG_OFFSET_CAPTURE);
        // If there were no <a> tags at all, we can safely replace everywhere
        if (empty($result)) {
            $parsedText = $this->createHyperlinks($text);
        } else {
            // If <a> tags were matched, loop on all positions
            $currentPosition = 0;
            $stringParts = [];
            foreach ($matches[0] as $matchInfo) {
                // The text between the current position and the next opening </a> tag is fetched
                // and the replacement is performed on it
                $beforeLink = substr($text, $currentPosition, $matchInfo[1] - $currentPosition);
                $stringParts[] = $this->createHyperlinks($beforeLink);
                // The text between the opening <a> tag and the closing </a> tag is taken as is
                // (no replacement, since we are inside a link)
                $linkEnd = strpos($text, '</a>', $matchInfo[1]) + 4;
                $stringParts[] = substr($text, $matchInfo[1], $linkEnd - $matchInfo[1]);
                // Move the current position within the string forward
                $currentPosition = $linkEnd;
            }
            // Take the last part and perform replacement on it
            $trailingPart = substr($text, $currentPosition);
            $stringParts[] = $this->createHyperlinks($trailingPart);
            // Assemble the whole string again
            $parsedText = implode('', $stringParts);
        }
        return $parsedText;
    }

    /**
     * Parses a string for URLs and makes links out of them.
     *
     * @param string $text
     * @return string
     */
    protected function createHyperlinks($text)
    {
        $parsedText = preg_replace('/(http:\/\/.*\/\\w+\/?)/', '<a href="$1">$1</a>', $text);
        return $parsedText;
    }
}
