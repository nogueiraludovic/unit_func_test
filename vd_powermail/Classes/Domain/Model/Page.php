<?php

declare(strict_types=1);

namespace Vd\VdPowermail\Domain\Model;

use In2code\Powermail\Domain\Model\Page as DefaultPage;

class Page extends DefaultPage
{
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdPowermail\Domain\Model\Field>
     */
    protected $fields;
}
