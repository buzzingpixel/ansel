<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\FileCache;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use BuzzingPixel\AnselConfig\Paths;
use DateTimeImmutable;
use SplFileInfo;

use function count;
use function explode;
use function is_array;
use function is_int;
use function json_decode;

class GetCacheItem
{
    private InternalFunctions $functions;

    private Paths $paths;

    public function __construct(
        InternalFunctions $functions,
        Paths $paths
    ) {
        $this->functions = $functions;
        $this->paths     = $paths;
    }

    /**
     * @throws InvalidArgument
     */
    public function get(string $key, CacheDirectory $directory): FileCacheItem
    {
        $keyParts = explode('/', $key);

        if (count($keyParts) !== 2) {
            throw new InvalidArgument();
        }

        $id = $keyParts[0];

        $fileName = $keyParts[1];

        $dirPath = $directory->getPath($this->paths);

        $metaPath = $dirPath . '/' . $id . '/meta.json';

        $fullPath = $dirPath . '/' . $id . '/' . $fileName;

        if (
            ! $this->functions->fileExists($metaPath) ||
            ! $this->functions->fileExists($fullPath)
        ) {
            return $this->returnEmpty($key);
        }

        $meta = json_decode(
            $this->functions->fileGetContents($metaPath),
            true
        );

        if (
            ! is_array($meta) ||
            ! isset($meta['time']) ||
            ! is_int($meta['time'])
        ) {
            return $this->returnEmpty($key);
        }

        return new FileCacheItem(
            $key,
            new SplFileInfo($fullPath),
            (new DateTimeImmutable())->setTimestamp(
                $meta['time'],
            ),
            is_int($meta['expires']) ?
                (new DateTimeImmutable())->setTimestamp(
                    $meta['expires'],
                ) :
                null,
        );
    }

    private function returnEmpty(string $key): FileCacheItem
    {
        return new FileCacheItem($key);
    }
}
