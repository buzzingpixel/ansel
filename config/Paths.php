<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig;

class Paths
{
    private string $anselCachePath;

    public function __construct(string $anselCachePath)
    {
        $this->anselCachePath = $anselCachePath;
    }

    public function anselCachePath(): string
    {
        return $this->anselCachePath;
    }
}
