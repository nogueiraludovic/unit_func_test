<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Service;

use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;

use function preg_replace_callback;
use function trim;

class LinkService
{
    protected ControllerContext $controllerContext;

    public function __construct(ControllerContext $controllerContext)
    {
        $this->controllerContext = $controllerContext;
    }

    public function parse(string $content, int $meetingId = 0): string
    {
        $content = preg_replace_callback(
            '/{ID_DEPUTE=(.*?)}/',
            function ($matches): string {
                return $this->generateLinkForDepute((int)trim($matches[1]));
            },
            $content
        );

        return preg_replace_callback(
            '/{ID_DETAILS=(.*?)}/',
            function ($matches) use ($meetingId): string {
                return $this->generateLinkForMeetingPoint(trim($matches[1]), $meetingId);
            },
            $content
        );
    }

    protected function generateLinkForDepute(int $memberId): string
    {
        return $this->controllerContext
            ->getUriBuilder()
            ->reset()
            ->setTargetPageUid(1029776)
            ->uriFor(
                'show',
                [
                    'memberId' => $memberId
                ],
                'Member'
            );
    }

    protected function generateLinkForMeetingPoint(string $pointId, int $meetingId): string
    {
        return $this->controllerContext
            ->getUriBuilder()
            ->reset()
            ->setTargetPageUid(2017368)
            ->uriFor(
                'gcPointShow',
                [
                    'meetingGcId' => $meetingId,
                    'pointId' => $pointId
                ],
                'Meeting'
            );
    }
}
