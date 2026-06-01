<?php

declare(strict_types=1);

namespace Vd\VdSite\Form\Element;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use Vd\VdSite\Imaging\TcaIconsProvider;

class InputIconPickerElement extends AbstractFormElement
{
    protected array $resultArray = [];

    public function render(): array
    {
        $this->resultArray = $this->initializeResultArray();
        $this
            ->addAssets()
            ->addHtml();

        return $this->resultArray;
    }

    protected function addAssets(): InputIconPickerElement
    {
        $this->resultArray['requireJsModules'][] = [
            'TYPO3/CMS/VdSite/Backend/icon-picker' => 'function (IconPicker) {
                IconPicker.initialize()
            }'
        ];

        $stylesheets = $this->getStylesheetFiles();

        foreach ($stylesheets as $stylesheet) {
            $this->resultArray['stylesheetFiles'][] = PathUtility::getAbsoluteWebPath(
                GeneralUtility::getFileAbsFileName($stylesheet)
            );
        }

        return $this;
    }

    protected function addHtml(): InputIconPickerElement
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName('EXT:vd_site/Resources/Private/Templates/Form/Element/IconPicker.html')
        );

        $this->resultArray['html'] = $view
            ->assignMultiple([
                'icons' => GeneralUtility::makeInstance(TcaIconsProvider::class)->getIcons(),
                'input' => [
                    'name' => $this->data['parameterArray']['itemFormElName'],
                    'value' => $this->data['parameterArray']['itemFormElValue']
                ]
            ])
            ->render();

        return $this;
    }

    protected function getStylesheetFiles(): array
    {
        $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('vd_site', __CLASS__);

        return $configuration['stylesheetFiles'] ?? [];
    }
}
