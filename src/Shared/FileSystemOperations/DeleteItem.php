<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\FileSystemOperations;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;

use const DIRECTORY_SEPARATOR;

class DeleteItem
{
    private InternalFunctions $internalFunctions;

    public function __construct(InternalFunctions $internalFunctions)
    {
        $this->internalFunctions = $internalFunctions;
    }

    public function delete(string $path): void
    {
        if (! $this->internalFunctions->isDir($path)) {
            $this->internalFunctions->unlink($path);

            return;
        }

        $objects = $this->internalFunctions->scanDir($path);

        foreach ($objects as $object) {
            if ($object === '.' || $object === '..') {
                continue;
            }

            $this->delete($path . DIRECTORY_SEPARATOR . $object);
        }

        $this->internalFunctions->rmdir($path);
    }
}
