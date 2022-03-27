<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Shared;

use function in_array;

class ImageMimeTypes
{
    public const JPEG = 'image/jpeg';

    public const PNG = 'image/png';

    public const GIF = 'image/gif';

    public const ALL = [
        self::JPEG => self::JPEG,
        self::PNG => self::PNG,
        self::GIF => self::GIF,
    ];

    public function mimeStringIsValidImage(string $mimeString): bool
    {
        return in_array($mimeString, self::ALL, true);
    }
}
