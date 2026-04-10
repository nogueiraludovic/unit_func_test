<?php

declare(strict_types=1);

namespace Vd\VdWebservice\Tests\Unit\Imaging;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use Vd\VdWebservice\Imaging\ImageConverter;
use Vd\VdWebservice\Query\FileReferenceQuery;

use function base64_encode;

final class ImageConverterTest extends TestCase
{
    private MockObject $fileReferenceQuery;
    private MockObject $resourceFactory;
    private ImageConverter $imageConverter;

    #[Test]
    public function base64ImageReturnsEmptyStringWhenNoFileReferenceFound(): void
    {
        $this->fileReferenceQuery->method('fetchOne')->willReturn(0);

        $this->assertSame('', $this->imageConverter->base64Image('image', 'some_table', 1));
    }

    #[Test]
    public function base64ImageReturnsEmptyStringWhenFileDoesNotExist(): void
    {
        $this->fileReferenceQuery->method('fetchOne')->willReturn(5);
        $this->resourceFactory->method('getFileObject')
            ->with(5)
            ->willThrowException(new FileDoesNotExistException('', 1));

        $this->assertSame('', $this->imageConverter->base64Image('image', 'some_table', 1));
    }

    #[Test]
    public function base64ImageReturnsEmptyStringWhenFileContentsIsEmpty(): void
    {
        $file = $this->createMock(File::class);
        $file->method('getContents')->willReturn('');

        $this->fileReferenceQuery->method('fetchOne')->willReturn(5);
        $this->resourceFactory->method('getFileObject')->willReturn($file);

        $this->assertSame('', $this->imageConverter->base64Image('image', 'some_table', 1));
    }

    #[Test]
    public function base64ImageReturnsBase64EncodedDataUri(): void
    {
        $file = $this->createMock(File::class);
        $file->method('getContents')->willReturn('binary-data');
        $file->method('getExtension')->willReturn('png');

        $this->fileReferenceQuery->method('fetchOne')->willReturn(5);
        $this->resourceFactory->method('getFileObject')->willReturn($file);

        $result = $this->imageConverter->base64Image('image', 'some_table', 1);

        $this->assertSame('data:image/png;base64,' . base64_encode('binary-data'), $result);
    }

    #[Test]
    public function base64ImageNormalizesJpegExtensionToJpg(): void
    {
        $file = $this->createMock(File::class);
        $file->method('getContents')->willReturn('jpeg-data');
        $file->method('getExtension')->willReturn('jpeg');

        $this->fileReferenceQuery->method('fetchOne')->willReturn(5);
        $this->resourceFactory->method('getFileObject')->willReturn($file);

        $result = $this->imageConverter->base64Image('image', 'some_table', 1);

        $this->assertStringStartsWith('data:image/jpg;base64,', $result);
    }

    #[Test]
    public function base64ImageFallsBackToPathinfoWhenExtensionIsEmpty(): void
    {
        $file = $this->createMock(File::class);
        $file->method('getContents')->willReturn('gif-data');
        $file->method('getExtension')->willReturn('');
        $file->method('getName')->willReturn('animation.gif');

        $this->fileReferenceQuery->method('fetchOne')->willReturn(5);
        $this->resourceFactory->method('getFileObject')->willReturn($file);

        $result = $this->imageConverter->base64Image('image', 'some_table', 1);

        $this->assertStringStartsWith('data:image/gif;base64,', $result);
    }

    #[Test]
    public function base64ImagePassesCorrectArgumentsToFetchOne(): void
    {
        $this->fileReferenceQuery->expects($this->once())
            ->method('fetchOne')
            ->with('thumbnail', 'tx_some_table', 42)
            ->willReturn(0);

        $this->imageConverter->base64Image('thumbnail', 'tx_some_table', 42);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileReferenceQuery = $this->createMock(FileReferenceQuery::class);
        $this->resourceFactory = $this->createMock(ResourceFactory::class);
        $this->imageConverter = new ImageConverter($this->fileReferenceQuery, $this->resourceFactory);
    }
}
