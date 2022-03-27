<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Psr;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;

class EmptyUploadedFile implements UploadedFileInterface
{
    public function getStream(): StreamInterface
    {
        throw new RuntimeException('This implementation is empty');
    }

    /**
     * @inheritDoc
     */
    public function moveTo($targetPath): void
    {
        throw new RuntimeException('This implementation is empty');
    }

    public function getSize(): ?int
    {
        throw new RuntimeException('This implementation is empty');
    }

    public function getError(): int
    {
        throw new RuntimeException('This implementation is empty');
    }

    public function getClientFilename(): ?string
    {
        throw new RuntimeException('This implementation is empty');
    }

    public function getClientMediaType(): ?string
    {
        throw new RuntimeException('This implementation is empty');
    }
}
