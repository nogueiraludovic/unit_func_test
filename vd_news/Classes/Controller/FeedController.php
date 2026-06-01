<?php

declare(strict_types=1);

namespace Vd\VdNews\Controller;

use GeorgRinger\News\Controller\NewsController;
use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Vd\VdCore\Mvc\PageNotFoundTrait;
use Vd\VdNews\Database\RecordRepository;

class FeedController extends NewsController
{
    use PageNotFoundTrait;

    public function initializeRssAction(): void
    {
        // @extensionScannerIgnoreLine
        $pid = $this->configurationManager->getContentObject()->data['uid'];
        $recordRepository = GeneralUtility::makeInstance(RecordRepository::class);
        $flexForm = $recordRepository->fetchFlexFormByPid($pid);

        $description = (string)$flexForm['settings']['displayXML']['xmlDesc'];

        if ($description !== '') {
            $this->settings['displayXML']['xmlDesc'] = $description;
        }

        $title = (string)$flexForm['settings']['displayXML']['xmlTitle'];

        if ($title !== '') {
            $this->settings['displayXML']['xmlTitle'] = $title;
        }

        if (isset($flexForm['settings']['startingpoint']) === true) {
            $this->settings['startingpoint'] = $flexForm['settings']['startingpoint'];
        }

        if (empty($flexForm) === true) {
            $fetchList = $recordRepository->fetchPagesListByPid($pid);
            $this->settings['startingpoint'] = $fetchList === '' ? $this->settings['startingpoint'] : $fetchList;
        }

        if ($this->hasRssFeed() === false) {
            $this->pageNotFoundAction();
        }

        $this->request->setFormat('xml');
    }

    public function rssAction(): void
    {
        $this->view->assign(
            'news',
            $this->newsRepository->findDemanded($this->createDemandObjectFromSettings($this->settings))
        );
    }

    protected function createDemandObjectFromSettings($settings, $class = NewsDemand::class): NewsDemand
    {
        $demand = parent::createDemandObjectFromSettings($settings, $class);

        if ($this->request->hasArgument('category') === false) {
            return $demand;
        }

        return $demand
            ->setCategories((array)$this->request->getArgument('category'))
            ->setCategoryConjunction('or')
            ->setStoragePage(0);
    }

    protected function getFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    protected function hasRssFeed(): bool
    {
        return $this->settings['displayXML']['xmlDesc'] !== ''
            && $this->settings['displayXML']['xmlTitle'] !== ''
            && $this->settings['startingpoint'] !== '';
    }
}
