<?php

declare(strict_types=1);

namespace Vd\VdCore\Authentication;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Crypto\Random;

trait AuthenticationTrait
{
    public function __construct(
        protected readonly BackendUserAuthentication $backendUser,
        protected readonly Random $random
    ) {
    }

    public function getFakeAdminUser(string $username): BackendUserAuthentication
    {
        // @extensionScannerIgnoreLine
        /** @noinspection PhpUndefinedFieldInspection */
        $this->backendUser->id = $this->random->generateRandomHexString(32);
        $this->backendUser->user = [
            'admin' => true,
            'uid' => 0,
            'username' => $username
        ];
        $this->backendUser->workspace = 0;

        $GLOBALS['BE_USER'] = $this->backendUser;

        return $this->backendUser;
    }
}
