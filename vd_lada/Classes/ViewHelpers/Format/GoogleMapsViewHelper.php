<?php

declare(strict_types=1);

namespace Vd\VdLada\ViewHelpers\Format;

use Closure;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

use function array_filter;
use function count;
use function implode;
use function nl2br;
use function str_replace;
use function strip_tags;
use function trim;

class GoogleMapsViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    protected $escapeChildren = false;
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('record', 'array', '');
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $record = $renderChildrenClosure();

        $address = nl2br(strip_tags($record['address']));
        $location = trim($record['zip']);

        if (isset($record['city']) === true && count($record['city']) > 0) {
            $location .= ' ' . $record['city'][0]['name'];
        }

        $searchQuery = '';

        if ($address !== '') {
            $searchQuery .= str_replace(' ', '+', $address);
        }

        if ($location !== '') {
            if ($searchQuery !== '') {
                $searchQuery .= ',';
            }

            $searchQuery .= str_replace(' ', '+', $location);
        }

        $fullAddress[] = $address;
        $fullAddress[] = $location;

        return '<a href="https://www.google.com/maps/place/' . $searchQuery . '" id="google-maps-link">'
            . '<ul class="list-unstyled mb-0">'
            . '<li>' . implode('</li><li>', array_filter($fullAddress)) . '</li>'
            . '</ul>'
            . '</a>';
    }
}
