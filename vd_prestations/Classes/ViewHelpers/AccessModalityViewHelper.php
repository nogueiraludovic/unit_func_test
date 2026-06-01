<?php

declare(strict_types=1);

namespace Vd\VdPrestations\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Vd\VdPrestations\Domain\Model\AccessModality;

use function getenv;
use function str_replace;
use function strlen;
use function strpos;

class AccessModalityViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('accessModality', AccessModality::class, '', true);
    }

    public function render(): string
    {
        $accessModality = $this->arguments['accessModality'];

        if ($accessModality->isExternalLink() === true) {
            return $accessModality->getUrl();
        }

        $url = $accessModality->getUrl();

        if (strpos($url, 'http') === 0) {
            return $accessModality->getUrl();
        }

        $domain = (string)getenv('TYPO3_EXT_VD_PRESTATIONS_ACCESS_MODALITY_URL');

        if (strpos($domain, 'https://') !== 0) {
            $domain = 'https://' . $domain;
        }

        if ($domain[strlen($domain) - 1] !== '/') {
            $domain .= '/';
        }

        if ($accessModality->getSecurityLevel() === 'PUBLIC') {
            $domain .= 'pub/';
        }

        return str_replace($domain . '/', $domain, $domain . $accessModality->getUrl());
    }
}
