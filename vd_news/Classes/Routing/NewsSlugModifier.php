<?php

declare(strict_types=1);

namespace Vd\VdNews\Routing;

class NewsSlugModifier
{
    public function modify(array $parameters): string
    {
        if ($parameters['record']['uid'] === 0) {
            return $parameters['slug'];
        }

        return $parameters['record']['uid']
            . ($parameters['configuration']['generatorOptions']['fieldSeparator'] ?? '/')
            . $parameters['slug'];
    }
}
