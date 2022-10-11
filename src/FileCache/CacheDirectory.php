<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\FileCache;

use BuzzingPixel\AnselConfig\Paths;
use MyCLabs\Enum\Enum;

/**
 * @extends Enum<string>
 */
class CacheDirectory extends Enum
{
    public const EPHEMERAL = 'ephemeral';

    public static function EPHEMERAL(): self
    {
        return new self(self::EPHEMERAL);
    }

    public const PERSISTENT = 'persistent';

    public static function PERSISTENT(): self
    {
        return new self(self::PERSISTENT);
    }

    public function getPath(Paths $paths): string
    {
        switch ($this->getValue()) {
            case self::EPHEMERAL:
                return $paths->anselCachePath();

            case self::PERSISTENT:
                return $paths->anselCachePathPersistent();

            default:
                // This isn't actually possible
                return '';
        }
    }
}
