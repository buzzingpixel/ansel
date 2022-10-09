<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\ImageManipulation;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use RuntimeException;
use SplFileInfo;

class GetImageSize
{
    private InternalFunctions $internalFunctions;

    public function __construct(InternalFunctions $internalFunctions)
    {
        $this->internalFunctions = $internalFunctions;
    }

    public function get(SplFileInfo $file): ImageSize
    {
        $size = $this->internalFunctions->getImageSize(
            $file->getPathname(),
        );

        if ($size === false) {
            throw new RuntimeException('Not a valid image');
        }

        return new ImageSize(
            $size[0],
            $size[1],
        );
    }
}
