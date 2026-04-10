<?php

declare(strict_types=1);

namespace Vd\VdCore\PageTitle;

use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

use function str_ends_with;

class ViewHelperTitleProvider extends AbstractPageTitleProvider
{
    public function setTitle(string $title): void
    {
        if ($this->title !== '') {
            return;
        }

        if (str_ends_with($title, ' | État de Vaud') === false) {
            $title .= ' | État de Vaud';
        }

        $this->title = $title;
    }
}
