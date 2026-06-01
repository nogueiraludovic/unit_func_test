<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Controller;

use DateTime;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use Vd\VdSafarinet\Evaluation\DateEvaluation;

use function count;
use function max;
use function sha1;

class MeetingController extends AbstractController
{
    protected ?DateEvaluation $dateEvaluation = null;

    public function gcListAction(): void
    {
        $meetings = $this->sielService->fetchGreatCouncilMeetings();

        if ($this->hasSoapClientError() === true) {
            return;
        }

        $nextMeetingsByYear = [];
        $passedMeetingsByYear = [];

        foreach ($meetings as $meeting) {
            $currentDate = (new DateTime('now'))->format('Y-m-d');
            $meetingDateTime = (new DateTime((string)$meeting['dateSeance']));

            if ($meetingDateTime->format('Y-m-d') < $currentDate) {
                $passedMeetingsByYear[$meetingDateTime->format('Y')][] = $meeting;
            } else {
                $nextMeetingsByYear[$meetingDateTime->format('Y')][] = $meeting;
            }
        }

        $this->view->assignMultiple([
            'nextMeetingsByYear' => $nextMeetingsByYear,
            'passedMeetingsByYear' => $passedMeetingsByYear
        ]);
    }

    public function gcMeetingShowAction(string $meetingId = null): void
    {
        if ($meetingId === null) {
            $this->pageNotFoundAction();
        }

        $cacheIdentifier = sha1('meeting_gc' . $meetingId);
        $hasCache = true;
        $meeting = $this->getCache($cacheIdentifier);

        if ($meeting === false) {
            $hasCache = false;
            $meeting = $this->sielService->fetchGreatCouncilMeeting((string)$meetingId);
        }

        if (count($meeting) === 0) {
            $this->pageNotFoundAction();
        }

        if ($this->hasSoapClientError() === true) {
            return;
        }

        if ($hasCache === false) {
            $this->setCache($cacheIdentifier, $meeting);
        }

        $this->view->assign('meeting', $meeting);
    }

    public function gcPointShowAction(string $pointId = null, string $meetingGcId = null): void
    {
        if ($pointId === null) {
            $this->pageNotFoundAction();
        }

        $cacheIdentifier = sha1('point_gc' . $pointId);
        $hasCache = true;
        $point = $this->getCache($cacheIdentifier);

        if ($point === false) {
            $hasCache = false;
            $point = $this->sielService->fetchGreatCouncilPoint((string)$pointId);
        }

        if (count($point) === 0) {
            $this->pageNotFoundAction();
        }

        if ($this->hasSoapClientError() === true) {
            return;
        }

        if ($hasCache === false) {
            $this->setCache($cacheIdentifier, $point);
        }

        $this->view->assignMultiple([
            'meetingGcId' => $meetingGcId,
            'point' => $point
        ]);
    }

    public function listAction(): void
    {
        if ($this->hasSearch() === true) {
            return;
        }

        $meetings = $this->sielService->fetchMeetings();

        if ($this->hasSoapClientError() === true) {
            return;
        }

        $paginator = new ArrayPaginator($meetings, $this->getPage(), 20);
        $pagination = new SimplePagination($paginator);

        $this->view->assignMultiple([
            'meetings' => $meetings,
            'pagination' => $pagination,
            'paginator' => $paginator
        ]);
    }

    public function showAction(string $meetingId = null): void
    {
        if ($meetingId === null) {
            $this->pageNotFoundAction();
        }

        $cacheIdentifier = sha1('meeting' . $meetingId);
        $hasCache = true;
        $meeting = $this->getCache($cacheIdentifier);

        if ($meeting === false) {
            $hasCache = false;
            $meeting = $this->sielService->fetchMeeting((string)$meetingId);
        }

        if (count($meeting) === 0) {
            $this->pageNotFoundAction();
        }

        if ($this->hasSoapClientError() === true) {
            return;
        }

        if ($hasCache === false) {
            $this->setCache($cacheIdentifier, $meeting);
        }

        $this->view->assign('meeting', $meeting);
    }

    protected function getPage(): int
    {
        if ($this->request->hasArgument('page') === true) {
            return max((int)$this->request->getArgument('page'), 1);
        }

        return 1;
    }

    protected function hasSearch(): bool
    {
        $request = $this->getRequest();

        $arguments = $request->getParsedBody() ?? $request->getQueryParams();

        return $arguments['tx_solr'] !== null;
    }
}
