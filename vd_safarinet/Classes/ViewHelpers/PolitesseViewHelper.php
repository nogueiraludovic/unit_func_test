<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\ViewHelpers;

use Closure;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function strtolower;

class PolitesseViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('text', 'mixed', '');
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $data = [
            'docteur' => 'Dr',
            'madame' => 'Mme',
            'mademoiselle' => 'Mlle',
            'maître' => 'Me',
            'mesdames' => 'Mmes',
            'mesdemoiselles' => 'Mlles',
            'messieurs' => 'MM.',
            'monsieur' => 'M.'
        ];

        return $data[strtolower((string)$arguments['text'])];
    }
}
