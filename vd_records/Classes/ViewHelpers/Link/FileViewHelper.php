<?php

declare(strict_types=1);

namespace Vd\VdRecords\ViewHelpers\Link;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;

use function htmlspecialchars;
use function http_build_query;
use function implode;
use function pathinfo;
use function preg_match;
use function rtrim;
use function strpos;

use const PATHINFO_EXTENSION;
use const PHP_QUERY_RFC3986;

// @TODO: use core ViewHelper since TYPO3 11
final class FileViewHelper extends AbstractTagBasedViewHelper
{
    protected $tagName = 'a';

    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument('download', 'bool', '');
        $this->registerArgument('file', FileInterface::class, '', true);
        $this->registerArgument('filename', 'string', '');
        $this->registerTagAttribute('name', 'string', '');
        $this->registerTagAttribute('rel', 'string', '');
        $this->registerTagAttribute('rev', 'string', '');
        $this->registerTagAttribute('target', 'string', '');
        $this->registerUniversalTagAttributes();
    }

    public function render(): string
    {
        $file = $this->arguments['file'];

        if (($file instanceof FileInterface) === false) {
            throw new Exception('Argument \'file\' must be an instance of ' . FileInterface::class, 1621511632);
        }

        $publicUrl = $file->getPublicUrl();

        if ($publicUrl === null) {
            return '';
        }

        if (strpos($publicUrl, 'dumpFile') !== false) {
            $publicUrl = $this->createFileDumpUrl($file);
        } elseif ($this->arguments['download'] ?? false) {
            $this->tag->addAttribute('download', $this->getAlternativeFilename($file));
        }

        $this->tag->addAttribute('href', $publicUrl);
        $this->tag->setContent($this->renderChildren() ?? htmlspecialchars($file->getName()));
        $this->tag->forceClosingTag(true);

        return $this->tag->render();
    }

    protected function createFileDumpUrl(FileInterface $file): string
    {
        $parameters = ['eID' => 'dumpFile'];

        if (($file instanceof File) === true) {
            $parameters['t'] = 'f';
            $parameters['f'] = $file->getUid();
        } elseif (($file instanceof FileReference) === true) {
            $parameters['t'] = 'r';
            $parameters['r'] = $file->getUid();
        } elseif (($file instanceof ProcessedFile) === true) {
            $parameters['t'] = 'p';
            $parameters['p'] = $file->getUid();
        }

        if ($download = $this->arguments['download'] ?? false) {
            $parameters['dl'] = (int)$download;
        }

        if (($filename = $this->getAlternativeFilename($file)) !== '') {
            $parameters['fn'] = $filename;
        }

        $parameters['token'] = GeneralUtility::hmac(implode('|', $parameters), 'resourceStorageDumpFile');

        return GeneralUtility::locationHeaderUrl(
            PathUtility::getAbsoluteWebPath(Environment::getPublicPath() . '/index.php')
        )
        . '?' . http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
    }

    protected function getAlternativeFilename(FileInterface $file): string
    {
        $alternativeFilename = $this->arguments['filename'] ?? '';

        if ($alternativeFilename === '' || (bool)preg_match('/^[0-9a-z._\-]+$/i', $alternativeFilename) === false) {
            return '';
        }

        $extension = pathinfo($alternativeFilename, PATHINFO_EXTENSION);

        if ($extension === '') {
            $alternativeFilename = rtrim($alternativeFilename, '.') . '.' . $file->getExtension();
        }

        return $file->getExtension() === pathinfo($alternativeFilename, PATHINFO_EXTENSION) ? $alternativeFilename : '';
    }
}
