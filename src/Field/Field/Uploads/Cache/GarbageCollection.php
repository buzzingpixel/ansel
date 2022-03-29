<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\Cache;

use BuzzingPixel\Ansel\Shared\ClockContract;
use BuzzingPixel\Ansel\Shared\FileSystemOperations\DeleteItem;
use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use BuzzingPixel\AnselConfig\Paths;
use DirectoryIterator;

use function assert;
use function json_decode;

use const DIRECTORY_SEPARATOR;

class GarbageCollection
{
    private Paths $paths;

    private ClockContract $clock;

    private DeleteItem $deleteItem;

    private InternalFunctions $internalFunctions;

    public function __construct(
        Paths $paths,
        ClockContract $clock,
        DeleteItem $deleteItem,
        InternalFunctions $internalFunctions
    ) {
        $this->paths             = $paths;
        $this->clock             = $clock;
        $this->deleteItem        = $deleteItem;
        $this->internalFunctions = $internalFunctions;
    }

    public function run(): void
    {
        $cachePath = $this->paths->anselCachePath();

        if (! $this->internalFunctions->isDir($cachePath)) {
            $this->internalFunctions->mkdir($cachePath);
        }

        $directory = new DirectoryIterator($cachePath);

        foreach ($directory as $fileInfo) {
            /** @phpstan-ignore-next-line */
            assert($fileInfo instanceof DirectoryIterator);

            if ($fileInfo->isDot() || ! $fileInfo->isDir()) {
                continue;
            }

            $this->processDirectory($fileInfo);
        }
    }

    private function processDirectory(DirectoryIterator $fileInfo): void
    {
        $directoryPath = $fileInfo->getPathname();

        $metaPath = $directoryPath . DIRECTORY_SEPARATOR . 'meta.json';

        if (! $this->internalFunctions->fileExists($metaPath)) {
            $this->deleteItem->delete($directoryPath);

            return;
        }

        $metaContents = $this->internalFunctions->fileGetContents(
            $metaPath,
        );

        $metaJson = (array) json_decode($metaContents, true);

        /** @phpstan-ignore-next-line */
        $expires = (int) ($metaJson['expires'] ?? 0);

        if ($expires > $this->clock->now()->getTimestamp()) {
            return;
        }

        $this->deleteItem->delete($directoryPath);
    }
}
