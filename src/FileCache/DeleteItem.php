<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\FileCache;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;

class DeleteItem
{
    private InternalFunctions $functions;

    private ClearCacheDirectory $clearCacheDirectory;

    public function __construct(
        InternalFunctions $functions,
        ClearCacheDirectory $clearCacheDirectory
    ) {
        $this->functions           = $functions;
        $this->clearCacheDirectory = $clearCacheDirectory;
    }

    public function delete(FileCacheItem $item): bool
    {
        $dirPath = $item->get()->getPath();

        $this->clearCacheDirectory->handleDirectoryPath($dirPath);

        $this->functions->rmdir($dirPath);

        return true;
    }
}
