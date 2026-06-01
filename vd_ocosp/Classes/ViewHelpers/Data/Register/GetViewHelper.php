<?php

namespace Vd\VdOcosp\ViewHelpers\Data\Register;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class GetViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this
            ->registerArgument('key', 'string', '', true)
            ->registerArgument('as', 'string', '', true);
    }

    public function render()
    {
        $key = $this->arguments['key'];
        $as = $this->arguments['as'];
        // Get the value from the register, if defined
        $register = self::getFrontendController()->register;
        if (array_key_exists($key, $register)) {
            $value = $register[$key];
        } else {
            $value = null;
        }
        // Load the value into the template and render children nodes
        $this->templateVariableContainer->add($as, $value);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($as);
        return $output;
    }

    protected static function getFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
