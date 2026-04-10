<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\Session;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Vd\VdSmallAds\DataTransferObject\Demand;

use function unserialize;

class SessionStorage implements SingletonInterface
{
    protected TypoScriptFrontendController $frontendController;

    public function __construct()
    {
        $this->frontendController = $this->getFrontendController();
    }

    public function get(string $key)
    {
        return $this->frontendController->fe_user->getKey('ses', $key);
    }

    public function getDemand(string $sessionIdentifier): mixed
    {
        return unserialize(
            $this->get($sessionIdentifier),
            [
                'allowed_classes' => [
                    Demand::class
                ]
            ]
        );
    }

    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    public function remove(string $key): void
    {
        $this->set($key, null);
    }

    public function set(string $key, $data): void
    {
        $this->frontendController->fe_user->setAndSaveSessionData($key, $data);
    }

    protected function getFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
