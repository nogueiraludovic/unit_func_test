<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Controller;

use function count;
use function sha1;

class ObjectController extends AbstractController
{
    public function showAction(string $objectId = null, string $memberId = null): void
    {
        if ($objectId === null) {
            $this->pageNotFoundAction();
        }

        $cacheIdentifier = sha1('object' . $objectId);
        $hasCache = true;
        $object = $this->getCache($cacheIdentifier);

        if ($object === false) {
            $hasCache = false;
            $object = $this->sielService->fetchObject((string)$objectId);
        }

        if (count($object) === 0) {
            $this->pageNotFoundAction();
        }

        if ($this->hasSoapClientError() === true) {
            return;
        }

        if ($hasCache === false) {
            $this->setCache($cacheIdentifier, $object);
        }

        $this->view->assignMultiple([
            'memberId' => $memberId,
            'object' => $object
        ]);
    }
}
