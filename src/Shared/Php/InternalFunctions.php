<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Php;

use function file_exists;
use function file_get_contents;
use function strtotime;
use function time;

class InternalFunctions
{
    public function time(): int
    {
        return time();
    }

    public function strToTime(string $datetime, ?int $baseTimestamp = null): int
    {
        if ($baseTimestamp !== null) {
            return (int) strtotime(
                $datetime,
                $baseTimestamp,
            );
        }

        return (int) strtotime($datetime);
    }

    public function fileExists(string $filename): bool
    {
        return file_exists($filename);
    }

    public function fileGetContents(string $filename): string
    {
        return (string) file_get_contents($filename);
    }
}
