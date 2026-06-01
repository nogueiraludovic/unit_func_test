<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Persistence;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class Session
{
    protected string $name = '';
    protected TypoScriptFrontendController $frontendController;

    public function __construct()
    {
        $this->frontendController = $this->getFrontendController();
    }

    public function exists(string $key): bool
    {
        $value = $this->frontendController->fe_user->getKey('ses', $this->name . $key);

        if ($value) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->frontendController->fe_user->getKey('ses', $this->name . $key);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function set(string $key, $value): Session
    {
        $this->frontendController->fe_user->setKey('ses', $this->name . $key, $value);
        // @extensionScannerIgnoreLine
        $this->frontendController->fe_user->storeSessionData();

        return $this;
    }

    public function setName(string $name): Session
    {
        $this->name = $name;

        return $this;
    }

    protected function getFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
