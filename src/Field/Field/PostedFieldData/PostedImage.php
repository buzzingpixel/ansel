<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

class PostedImage
{
    private string $uid;

    private string $sourceImageId;

    private string $x;

    private string $y;

    private string $width;

    private string $height;

    private string $cacheDirectory;

    private string $cacheFilePath;

    private string $fileName;

    /**
     * @param mixed[] $postedImage
     */
    public static function fromArray(array $postedImage): self
    {
        return new self(
            /** @phpstan-ignore-next-line */
            (string) ($postedImage['uid'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($postedImage['sourceImageId'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($postedImage['x'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($postedImage['y'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($postedImage['width'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($postedImage['height'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($postedImage['cacheDirectory'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($postedImage['cacheFilePath'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($postedImage['fileName'] ?? ''),
        );
    }

    public function __construct(
        string $uid,
        string $sourceImageId,
        string $x,
        string $y,
        string $width,
        string $height,
        string $cacheDirectory,
        string $cacheFilePath,
        string $fileName
    ) {
        $this->uid            = $uid;
        $this->sourceImageId  = $sourceImageId;
        $this->x              = $x;
        $this->y              = $y;
        $this->width          = $width;
        $this->height         = $height;
        $this->cacheDirectory = $cacheDirectory;
        $this->cacheFilePath  = $cacheFilePath;
        $this->fileName       = $fileName;
    }

    public function uid(): string
    {
        return $this->uid;
    }

    public function sourceImageId(): string
    {
        return $this->sourceImageId;
    }

    public function x(): string
    {
        return $this->x;
    }

    public function y(): string
    {
        return $this->y;
    }

    public function width(): string
    {
        return $this->width;
    }

    public function height(): string
    {
        return $this->height;
    }

    public function cacheDirectory(): string
    {
        return $this->cacheDirectory;
    }

    public function cacheFilePath(): string
    {
        return $this->cacheFilePath;
    }

    public function fileName(): string
    {
        return $this->fileName;
    }
}
