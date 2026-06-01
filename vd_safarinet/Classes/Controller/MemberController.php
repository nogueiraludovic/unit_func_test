<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Controller;

use function count;
use function sha1;
use function strtoupper;
use function substr;

class MemberController extends AbstractController
{
    public function districtAction(): void
    {
        $members = $this->sielService->fetchMembersByDistrict();

        if ($this->hasSoapClientError() === true) {
            return;
        }

        $this->view->assign('districts', $members);
    }

    public function listAction(): void
    {
        $members = $this->sielService->fetchMembersByName();

        if ($this->hasSoapClientError() === true) {
            return;
        }

        $this->view->assign('groups', $this->groupByFirstLetter($members));
    }

    public function partyAction(): void
    {
        $members = $this->sielService->fetchMembersByParty();

        if ($this->hasSoapClientError() === true) {
            return;
        }

        $this->view->assign('parties', $members);
    }

    public function showAction(string $memberId = null): void
    {
        if ($memberId === null) {
            $this->pageNotFoundAction();
        }

        $cacheIdentifier = sha1('member' . $memberId);
        $hasCache = true;
        $member = $this->getCache($cacheIdentifier);

        if ($member === false) {
            $hasCache = false;
            $member = $this->sielService->fetchMember((string)$memberId);
        }

        if (count($member) === 0) {
            $this->pageNotFoundAction();
        }

        if ($this->hasSoapClientError() === true) {
            return;
        }

        if ($hasCache === false) {
            $this->setCache($cacheIdentifier, $member);
        }

        $this->view->assign('member', $member);
    }

    protected function groupByFirstLetter(array $members): array
    {
        $groups = [];

        foreach ($members as $member) {
            $groups[strtoupper(substr((string)$member['nom'], 0, 1))][] = $member;
        }

        return $groups;
    }
}
