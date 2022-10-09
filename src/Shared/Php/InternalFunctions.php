<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Php;

use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function getimagesize;
use function is_dir;
use function mkdir;
use function rmdir;
use function scandir;
use function strtotime;
use function time;
use function unlink;

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

    /**
     * @param mixed $data
     *
     * @return int|false
     */
    public function filePutContents(
        string $filename,
        $data,
        int $flags = 0
    ) {
        return file_put_contents(
            $filename,
            $data,
            $flags,
        );
    }

    public function mkdir(
        string $directory,
        int $permissions = 0777,
        bool $recursive = true
    ): bool {
        return mkdir(
            $directory,
            $permissions,
            $recursive,
        );
    }

    public function isDir(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * @return string[]
     */
    public function scanDir(string $path): array
    {
        /** @phpstan-ignore-next-line */
        return scandir($path);
    }

    public function rmdir(string $path): bool
    {
        return rmdir($path);
    }

    public function unlink(string $path): bool
    {
        return unlink($path);
    }

    public function doExit(): void
    {
        exit;
    }

    /**
     * @param mixed[] $imageInfo
     *
     * @return array{0: int, 1: int}|false
     */
    public function getImageSize(string $filename, array &$imageInfo = [])
    {
        /** @phpstan-ignore-next-line */
        return getimagesize($filename, $imageInfo);
    }
}
