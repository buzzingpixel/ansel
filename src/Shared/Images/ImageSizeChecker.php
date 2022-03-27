<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared\Images;

use Exception;
use FastImageSize\FastImageSize;

use function is_bool;

class ImageSizeChecker
{
    private FastImageSize $fastImageSize;

    public function __construct(FastImageSize $fastImageSize)
    {
        $this->fastImageSize = $fastImageSize;
    }

    /**
     * @throws Exception
     */
    public function getImageSize(string $imagePath): ImageSizeResult
    {
        $result = $this->fastImageSize->getImageSize(
            $imagePath,
        );

        if (is_bool($result)) {
            throw new Exception();
        }

        return new ImageSizeResult(
            (int) ($result['width'] ?? 0),
            (int) ($result['height'] ?? 0),
        );
    }
}
