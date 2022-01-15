<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Php;

use function file_get_contents;

class InternalFunctions
{
    public function fileGetContents(string $filename): string
    {
        return (string) file_get_contents($filename);
    }
}
