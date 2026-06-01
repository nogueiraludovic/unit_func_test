<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\DataProcessing;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

abstract class AbstractProcessor implements DataProcessorInterface
{
    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
