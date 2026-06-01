<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\DataProcessing;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use Vd\VdSafarinet\Service\SielService;

use function count;
use function sha1;

class SielProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        $arguments = $this->getRequest()->getQueryParams();
        $arguments = $arguments['tx_vdsafarinet_safarinet'];

        if ($arguments === null) {
            return $processedData;
        }

        $cacheKey = '';
        $id = '';
        $key = '';
        $method = '';

        switch ($arguments['action']) {
            case 'gcMeetingShow':
                $cacheKey = 'meeting_gc';
                $id = $arguments['meetingId'];
                $key = 'sielMeetingGcData';
                $method = 'fetchGreatCouncilMeeting';
                break;
            case 'gcPointShow':
                $cacheKey = 'point_gc';
                $id = $arguments['pointId'];
                $key = 'sielPointGcData';
                $method = 'fetchGreatCouncilPoint';
                break;
            case 'show':
                switch ($arguments['controller']) {
                    case 'Decision':
                        $cacheKey = 'decision';
                        $id = $arguments['decisionId'];
                        $key = 'sielDecisionData';
                        $method = 'fetchDecision';
                        break;
                    case 'Group':
                        $cacheKey = 'group';
                        $id = $arguments['groupId'];
                        $key = 'sielGroupData';
                        $method = 'fetchGroup';
                        break;
                    case 'Meeting':
                        $cacheKey = 'meeting';
                        $id = $arguments['meetingId'];
                        $key = 'sielMeetingData';
                        $method = 'fetchMeeting';
                        break;
                    case 'Member':
                        $cacheKey = 'member';
                        $id = $arguments['memberId'];
                        $key = 'sielMemberData';
                        $method = 'fetchMember';
                        break;
                    case 'Object':
                        $cacheKey = 'object';
                        $id = $arguments['objectId'];
                        $key = 'sielObjectData';
                        $method = 'fetchObject';
                        break;
                }
                break;
        }

        if ($cacheKey === '' && $id === '' && $key === '' && $method === '') {
            return $processedData;
        }

        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);

        if ($cacheManager->hasCache('vd_safarinet') === true) {
            $cache = $cacheManager->getCache('vd_safarinet')->get(sha1($cacheKey . $id));

            if ($cache !== false) {
                $processedData[$key] = $cache;

                return $processedData;
            }
        }

        $member = GeneralUtility::makeInstance(SielService::class)->{$method}($id);

        if (count($member) === 0) {
            return $processedData;
        }

        $processedData[$key] = $member;

        return $processedData;
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
