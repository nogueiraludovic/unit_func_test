<?php

declare(strict_types=1);

namespace Vd\VdNews\EventListener;

use GeorgRinger\News\Event\NewsDetailActionEvent;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\VisibilityAspect;
use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\Service\TypoLinkCodecService;
use Vd\VdNews\Database\RecordRepository;

use function is_array;

final class NewsDetailActionEventListener
{
    public function __invoke(NewsDetailActionEvent $event)
    {
        $assignedValues = $event->getAssignedValues();
        $newsItem = $assignedValues['newsItem'];

        if ($newsItem === null) {
            return;
        }

        $newsType = $newsItem->getType();

        if ($newsType === 0) {
            return;
        }

        switch ($newsType) {
            case 1:
                $internalUrl = $newsItem->getInternalUrl();

                $typoLinkCodec = GeneralUtility::makeInstance(TypoLinkCodecService::class);
                $typoLinkConfiguration = $typoLinkCodec->decode($internalUrl);

                $link = GeneralUtility::makeInstance(LinkService::class)
                    ->resolve($typoLinkConfiguration['url'] ?? '');

                if ($link['type'] !== 'page') {
                    return;
                }

                $linkUid = (int)$link['pageuid'];

                if ($linkUid === 0) {
                    return;
                }

                $context = clone GeneralUtility::makeInstance(Context::class);
                $context->setAspect('visibility', new VisibilityAspect(true));

                $url = (string)GeneralUtility::makeInstance(SiteFinder::class)
                    ->getSiteByPageId(1000001)
                    ->getRouter($context)
                    ->generateUri($linkUid);
                break;
            case 2:
                $url = $newsItem->getExternalUrl();
                break;
            case 3:
                $newsItem = $newsItem->getSelectedArticle();

                if ($newsItem === null) {
                    $arguments = $event->getRequest()->getArguments();
                    $recordRepository = GeneralUtility::makeInstance(RecordRepository::class);

                    $newsItem = $recordRepository->fetchByUid((int)$arguments['news_preview'], false);
                    $newsItem = $recordRepository->fetchByUid((int)$newsItem['selected_article'], false);
                }

                $uriBuilder = GeneralUtility::makeInstance(ObjectManager::class)->get(UriBuilder::class);
                $uriBuilder->reset();

                if (is_array($newsItem) === true) {
                    $isHidden = (bool)$newsItem['hidden'];
                    $newsPreview = (int)$newsItem['uid'];
                } else {
                    $isHidden = $newsItem->getHidden();
                    $newsPreview = $newsItem->getUid();
                }

                if ($isHidden === true) {
                    $uriBuilder->setArguments([
                        'isPreview' => true
                    ]);
                }

                $url = $uriBuilder
                    ->setCreateAbsoluteUri(true)
                    ->uriFor(
                        'detail',
                        [
                            'news_preview' => $newsPreview
                        ],
                        'News',
                        'News',
                        'Pi1'
                    );
                break;
            default:
                $url = '';
        }

        if (GeneralUtility::isValidUrl($url) === false) {
            return;
        }

        HttpUtility::redirect($url);
    }
}
