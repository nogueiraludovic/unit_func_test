<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Controller;

use function count;
use function sha1;

class DecisionController extends AbstractController
{
    public function showAction(string $decisionId = null): void
    {
        if ($decisionId === null) {
            $this->pageNotFoundAction();
        }

        $cacheIdentifier = sha1('decision' . $decisionId);
        $hasCache = true;
        $decision = $this->getCache($cacheIdentifier);

        if ($decision === false) {
            $decision = $this->sielService->fetchDecision((string)$decisionId);
            $hasCache = false;
        }

        if (count($decision) === 0) {
            $this->pageNotFoundAction();
        }

        if ($this->hasSoapClientError() === true) {
            return;
        }

        if ($hasCache === false) {
            $this->setCache($cacheIdentifier, $decision);
        }

        $this->view->assign('decision', $decision);
    }
}
