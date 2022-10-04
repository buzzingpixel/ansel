<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\PostedFieldData;

class PostedImageUpload
{
    private string $cacheDirectory;

    private string $cacheFilePath;

    private string $fileName;

    /**
     * @param mixed[] $arrayData
     */
    public static function fromArray(array $arrayData): self
    {
        return new self(
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['cacheDirectory'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['cacheFilePath'] ?? ''),
            /** @phpstan-ignore-next-line */
            (string) ($arrayData['fileName'] ?? ''),
        );
    }

    public function __construct(
        string $cacheDirectory,
        string $cacheFilePath,
        string $fileName
    ) {
        $this->cacheDirectory = $cacheDirectory;
        $this->cacheFilePath  = $cacheFilePath;
        $this->fileName       = $fileName;
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

    /**
     * @return mixed[]
     */
    public function asScalarArray(): array
    {
        return [
            'cacheDirectory' => $this->cacheDirectory(),
            'cacheFilePath' => $this->cacheFilePath(),
            'fileName' => $this->fileName(),
        ];
    }
}
