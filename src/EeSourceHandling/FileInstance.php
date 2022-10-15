<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\EeSourceHandling;

use function pathinfo;

class FileInstance implements File
{
    /** @var class-string<AnselSourceAdapter> */
    private string $sourceAdapterClass;

    private string $identifier;

    private string $sourceIdentifier;

    private ?string $path;

    private string $url;

    private string $baseName;

    private string $fileName;

    private string $fileNameExtension;

    private int $filesize;

    protected int $width;

    private int $height;

    /**
     * @param class-string<AnselSourceAdapter> $sourceAdapterClass
     */
    public function __construct(
        string $sourceAdapterClass,
        string $identifier,
        string $sourceIdentifier,
        ?string $path,
        string $url,
        int $filesize,
        int $width,
        int $height
    ) {
        $this->sourceAdapterClass = $sourceAdapterClass;

        $this->identifier = $identifier;

        $this->sourceIdentifier = $sourceIdentifier;

        $pathInfo = pathinfo($url);

        $this->path = $path;

        $this->url = $url;

        $this->baseName = $pathInfo['basename'];

        $this->fileName = $pathInfo['filename'];

        $this->fileNameExtension = $pathInfo['extension'] ?? '';

        $this->filesize = $filesize;

        $this->width = $width;

        $this->height = $height;
    }

    /**
     * @return class-string<AnselSourceAdapter>
     */
    public function sourceAdapterClass(): string
    {
        return $this->sourceAdapterClass;
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function sourceIdentifier(): string
    {
        return $this->sourceIdentifier;
    }

    public function path(): ?string
    {
        return $this->path;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function pathOrUrl(): string
    {
        return $this->path() ??
            $this->url();
    }

    public function baseName(): string
    {
        return $this->baseName;
    }

    public function fileName(): string
    {
        return $this->fileName;
    }

    public function fileNameExtension(): string
    {
        return $this->fileNameExtension;
    }

    public function filesize(): int
    {
        return $this->filesize;
    }

    public function width(): int
    {
        return $this->width;
    }

    public function height(): int
    {
        return $this->height;
    }
}
