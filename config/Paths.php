<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig;

class Paths
{
    private string $anselCachePath;

    private string $anselCachePathPersistent;

    public function __construct(
        string $anselCachePath,
        string $anselCachePathPersistent
    ) {
        $this->anselCachePath           = $anselCachePath;
        $this->anselCachePathPersistent = $anselCachePathPersistent;
    }

    public function anselCachePath(): string
    {
        return $this->anselCachePath;
    }

    public function anselCachePathPersistent(): string
    {
        return $this->anselCachePathPersistent;
    }
}
