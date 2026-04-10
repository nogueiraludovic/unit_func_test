<?php

declare(strict_types=1);

namespace Vd\VdCore\Log\Writer;

use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Throwable;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Log\LogRecord;
use TYPO3\CMS\Core\Log\Writer\FileWriter as DefaultFileWriter;
use TYPO3\CMS\Core\Log\Writer\WriterInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

use function count;
use function date;
use function fwrite;
use function json_encode;
use function sprintf;
use function strtoupper;

use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;
use const LF;

class FileWriter extends DefaultFileWriter
{
    public function writeLog(LogRecord $record): WriterInterface
    {
        $context = $record->getData();
        $message = $record->getMessage();

        if (count($context) > 0) {
            if (isset($context['exception']) === true && ($context['exception'] instanceof Throwable) === true) {
                $message .= $this->formatException($context['exception']);
                $context['exception'] = (string)$context['exception'];
            }

            $data = '- ' . json_encode($context, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $message = sprintf(
            '%s [%s] pageUid="%d" component="%s": %s %s',
            date('r', (int)$record->getCreated()),
            strtoupper($record->getLevel()),
            $this->getPageUid(),
            $record->getComponent(),
            $this->interpolate($message, $context),
            $data ?? ''
        );

        if (fwrite(self::$logFileHandles[$this->logFile], $message . LF) === false) {
            throw new RuntimeException('Could not write log record to log file', 1345036335);
        }

        return $this;
    }

    protected function getPageUid(): int
    {
        $request = $this->getRequest();

        if (($request instanceof ServerRequestInterface) === false) {
            return 0;
        }

        if (ApplicationType::fromRequest($request)->isFrontend() === true) {
            $frontendController = $request->getAttribute('frontend.controller');

            if (($frontendController instanceof TypoScriptFrontendController) === false) {
                return 0;
            }

            // @extensionScannerIgnoreLine
            return (int)$frontendController->id;
        }

        $queryParams = $request->getQueryParams();

        return (int)($queryParams['id'] ?? 0);
    }

    protected function getRequest(): ?ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'] ?? null;
    }
}
