<?php

declare(strict_types=1);

namespace Vd\VdPrestations\ViewHelpers;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Vd\VdPrestations\Domain\Model\AccessModality;

use function implode;
use function strtolower;
use function ucfirst;

class GetHighLightTitlesViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('before', 'string', '');
    }

    public function render(): string
    {
        /** @var AccessModality $accessModality */
        $accessModality = $this->templateVariableContainer->get('accessModality');
        $titles = [];

        if ($accessModality->getRequiredDocuments() !== '') {
            $titles[] = LocalizationUtility::translate('prestation.highlight.required_documents', 'vd_prestations');
        }

        if ($accessModality->getCost() !== '') {
            $titles[] = LocalizationUtility::translate('prestation.highlight.cost', 'vd_prestations');
        }

        if ($accessModality->getAverageDelay() !== '') {
            $titles[] = LocalizationUtility::translate('prestation.highlight.average_delay', 'vd_prestations');
        }

        $formattedTitle = implode(', ', $titles);

        return $this->arguments['before']
            . ($formattedTitle !== '' && $this->arguments['before'] !== '' ? ': ' : '')
            . ucfirst(strtolower($formattedTitle));
    }
}
