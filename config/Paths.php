<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig;

use Craft;
use RuntimeException;

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

    public function systemCachePath(): string
    {
        switch (ANSEL_ENV) {
            case 'ee':
                $sysPath = rtrim(realpath(SYSPATH), '/') . '/';
                return $sysPath . 'user/cache';
            case 'craft':
                return rtrim(Craft::getAlias('@storage'), '/');
            default:
                $msg = 'Not implemented for platform ';

                throw new RuntimeException(
                    $msg . ANSEL_ENV,
                );
        }
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
