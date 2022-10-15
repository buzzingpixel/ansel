<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use League\MimeTypeDetection\FinfoMimeTypeDetector;

class MimeTypeDetector
{
    private FinfoMimeTypeDetector $detector;

    public function __construct(FinfoMimeTypeDetector $detector)
    {
        $this->detector = $detector;
    }

    /**
     * Detects from finfo and falls back to extension if that fails
     */
    public function detect(string $path): string
    {
        $mimeType = $this->detector->detectMimeTypeFromFile($path);

        if ($mimeType !== null && $mimeType !== '') {
            return $mimeType;
        }

        return (string) $this->detector->detectMimeTypeFromPath($path);
    }
}
