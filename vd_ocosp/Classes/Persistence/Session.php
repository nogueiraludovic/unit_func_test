<?php

namespace Vd\VdOcosp\Persistence;

use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class Session
{
    /**
     * @var FrontendUserAuthentication
     */
    protected $user;

    public function __construct()
    {
        $this->user = $this->getFrontendController()->fe_user;
    }

    public function set(string $key, $value): void
    {
        $this->user->setSessionData($key, $value);
    }

    public function get(string $key)
    {
        return $this->user->getSessionData($key);
    }

    protected function getFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
