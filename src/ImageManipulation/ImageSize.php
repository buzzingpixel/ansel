<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\ImageManipulation;

class ImageSize
{
    private int $width;

    private int $height;

    public function __construct(
        int $width,
        int $height
    ) {
        $this->width  = $width;
        $this->height = $height;
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
