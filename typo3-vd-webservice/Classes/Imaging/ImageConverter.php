<?php

declare(strict_types=1);

namespace Vd\VdWebservice\Imaging;

use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use Vd\VdWebservice\Query\FileReferenceQuery;

use function base64_encode;
use function is_string;
use function pathinfo;

use const PATHINFO_EXTENSION;

final readonly class ImageConverter
{
    public function __construct(
        private FileReferenceQuery $fileReferenceQuery,
        private ResourceFactory $resourceFactory
    ) {
    }

    public function base64Image(string $fieldname, string $tablenames, int $uidForeign): string
    {
        $sysFileUid = $this->fileReferenceQuery->fetchOne($fieldname, $tablenames, $uidForeign);

        if ($sysFileUid <= 0) {
            return '';
        }

        try {
            $file = $this->resourceFactory->getFileObject($sysFileUid);
        } catch (FileDoesNotExistException) {
            return '';
        }

        $contents = $file->getContents();

        if (is_string($contents) === false || $contents === '') {
            return '';
        }

        $extension = $file->getExtension();

        if ($extension === '') {
            $extension = (string)pathinfo($file->getName(), PATHINFO_EXTENSION);
        }

        if ($extension === 'jpeg') {
            $extension = 'jpg';
        }

        return 'data:image/' . $extension . ';base64,' . base64_encode($contents);
    }
}
