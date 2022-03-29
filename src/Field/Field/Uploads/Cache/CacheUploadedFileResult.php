<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\Cache;

class CacheUploadedFileResult
{
    private bool $success;

    private string $message;

    private string $fileName;

    private string $cacheDirectory;

    private string $cacheFilePath;

    private string $base64Image;

    public function __construct(
        bool $success,
        string $message = '',
        string $fileName = '',
        string $cacheDirectory = '',
        string $cacheFilePath = '',
        string $base64Image = ''
    ) {
        $this->success        = $success;
        $this->message        = $message;
        $this->fileName       = $fileName;
        $this->cacheDirectory = $cacheDirectory;
        $this->cacheFilePath  = $cacheFilePath;
        $this->base64Image    = $base64Image;
    }

    public function success(): bool
    {
        return $this->success;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function fileName(): string
    {
        return $this->fileName;
    }

    public function cacheDirectory(): string
    {
        return $this->cacheDirectory;
    }

    public function cacheFilePath(): string
    {
        return $this->cacheFilePath;
    }

    public function base64Image(): string
    {
        return $this->base64Image;
    }

    /**
     * @return string[]
     */
    public function asArray(): array
    {
        return [
            'type' => 'success',
            'message' => $this->message(),
            'fileName' => $this->fileName(),
            'cacheDirectory' => $this->cacheDirectory(),
            'cacheFilePath' => $this->cacheFilePath(),
            'base64Image' => $this->base64Image(),
        ];
    }
}
