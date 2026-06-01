<?php

declare(strict_types=1);

namespace Vd\VdSafarinet\Controller;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Vd\VdCore\Mvc\PageNotFoundTrait;
use Vd\VdSafarinet\Service\SielService;

abstract class AbstractController extends ActionController
{
    use LoggerAwareTrait, PageNotFoundTrait;
    use LoggerAwareTrait;
    use PageNotFoundTrait;

    protected CacheManager $cacheManager;
    protected bool $debug = false;
    protected SielService $sielService;

    public function __construct()
    {
        // @extensionScannerIgnoreLine
        $this->debug = (bool)GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('vd_safarinet', 'debugVerbose');

        if (($this->logger instanceof LoggerInterface) === false) {
            $this->setLogger(GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__));
        }
    }

    public function injectCacheManager(CacheManager $cacheManager): void
    {
        $this->cacheManager = $cacheManager;
    }

    public function injectSielService(SielService $sielService): void
    {
        $this->sielService = $sielService;
    }

    protected function getCache(string $identifier)
    {
        if ($this->cacheManager->hasCache('vd_safarinet') === false) {
            return false;
        }

        $cache = $this->cacheManager->getCache('vd_safarinet')->get($identifier);

        $this->log('[CACHE] Cache found for identifier: ' . $identifier, ($cache === false ? [false] : $cache));

        return $cache;
    }

    protected function getErrorFlashMessage(): bool
    {
        return false;
    }

    protected function getFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    protected function hasSoapClientError(): bool
    {
        if ($this->sielService->hasError() === false) {
            return false;
        }

        $this->view->assign('error', true);

        return true;
    }

    protected function initializeView(ViewInterface $view): void
    {
        $view->assign('pageUid', $this->getFrontendController()->id);
    }

    protected function log(string $message, array $context = []): void
    {
        // @extensionScannerIgnoreLine
        if ($this->debug === false) {
            return;
        }

        // @extensionScannerIgnoreLine
        $this->logger->info($message, $context);
    }

    protected function setCache(string $identifier, array $data): void
    {
        $tags = ['pageId_' . $this->getRequest()->getAttribute('routing')->getPageId()];
        $timeout = $this->getFrontendController()->page['cache_timeout'];

        $this->log('[ADD-CACHE] Add cache entry for identifier: ' . $identifier, [
            'cache_expire' => $GLOBALS['EXEC_TIME'] + $timeout,
            'data' => $data,
            'tags' => $tags,
            'timeout' => $timeout
        ]);

        $this->cacheManager->getCache('vd_safarinet')->set($identifier, $data, $tags, $timeout);
    }
}
