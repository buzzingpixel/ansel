<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\FileCache;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use BuzzingPixel\AnselConfig\Paths;
use DirectoryIterator;

class ClearCacheDirectory
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

    public function clear(CacheDirectory $directory): void
    {
        $dirPath = $directory->getPath($this->paths);

        // Can't confirm that this would throw on Windows,
        // And there seems to be a delay from the result of rm -rf anyway
        // making the iterator always run anway so this isn't worth doing
        // I guess
        // try {
        //     shell_exec('rm -rf ' . $dirPath . '/*');
        // } catch (Throwable $e) {
        // }

        $this->handleDirectoryPath($dirPath);
    }

    public function handleDirectoryPath(string $path): void
    {
        $dirIterator = new DirectoryIterator($path);

        foreach ($dirIterator as $fileInfo) {
            $this->handleFileOrDirectory($fileInfo);
        }
    }

    public function handleFileOrDirectory(DirectoryIterator $fileInfo): void
    {
        if ($fileInfo->isDot()) {
            return;
        }

        if ($fileInfo->isDir()) {
            $this->handleDirectoryPath($fileInfo->getPathname());

            $this->functions->rmdir($fileInfo->getPathname());
        }

        if (! $fileInfo->isFile()) {
            return;
        }

        $this->functions->unlink($fileInfo->getPathname());
    }
}
