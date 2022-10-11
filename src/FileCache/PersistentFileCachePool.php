<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\FileCache;

class PersistentFileCachePool extends FileCachePool
{
    public static function cacheDirectory(): CacheDirectory
    {
        return CacheDirectory::PERSISTENT();
    }
}
