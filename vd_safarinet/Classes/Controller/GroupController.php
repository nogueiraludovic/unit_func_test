<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Controller;

use function count;
use function sha1;

class GroupController extends AbstractController
{
    public function showAction(string $groupId = null, string $memberId = null): void
    {
        if ($groupId === null) {
            $this->pageNotFoundAction();
        }

        $cacheIdentifier = sha1('group' . $groupId);
        $hasCache = true;
        $group = $this->getCache($cacheIdentifier);

        if ($group === false) {
            $group = $this->sielService->fetchGroup((string)$groupId);
            $hasCache = false;
        }

        if (count($group) === 0) {
            $this->pageNotFoundAction();
        }

        if ($this->hasSoapClientError() === true) {
            return;
        }

        if ($hasCache === false) {
            $this->setCache($cacheIdentifier, $group);
        }

        $this->view->assignMultiple([
            'group' => $group,
            'memberId' => $memberId
        ]);
    }
}
