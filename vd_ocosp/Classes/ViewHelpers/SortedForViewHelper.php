<?php

namespace Vd\VdOcosp\ViewHelpers;

use Closure;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper;

/**
 * Extends the default Fluid "for" view helper by sorting the incoming data first
 *
 * = Examples =
 *
 * <code title="Simple Loop">
 * <vd:sortedFor each="{users}" as="user" sort="name.lastName" order="desc">{user.name}</vd:sortedFor>
 * </code>
 * <output>
 * Will display a list of users sorted by last names, descending
 * </output>
 */
class SortedForViewHelper extends ForViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('sort', 'string', 'Sorting field', false, '');
        $this->registerArgument('order', 'string', 'Order', false, 'asc');
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        // If a sort property is defined, assemble results again, using the sort property as key
        if (!empty($arguments['sort'])) {
            $sortedEach = [];
            foreach ($arguments['each'] as $item) {
                $sortingKey = '';
                if (is_array($item) && isset($item[$arguments['sort']])) {
                    $sortingKey = $item[$arguments['sort']];
                } elseif (is_object($item)) {
                    $sortingKey = ObjectAccess::getPropertyPath($item, $arguments['sort']);
                }
                $sortedEach[$sortingKey] = $item;
            }
            // Sort results
            // Verify order and enforce "asc" as default
            $order = strtolower($arguments['order']);
            if ($order === 'desc') {
                krsort($sortedEach);
            } else {
                ksort($sortedEach);
            }
            $arguments['each'] = $sortedEach;
        }

        return parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext);
    }
}
