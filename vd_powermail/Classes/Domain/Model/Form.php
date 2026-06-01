<?php

declare(strict_types=1);

namespace Vd\VdPowermail\Domain\Model;

use In2code\Powermail\Domain\Model\Form as DefaultForm;

class Form extends DefaultForm
{
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Vd\VdPowermail\Domain\Model\Page>
     */
    protected $pages;
}
