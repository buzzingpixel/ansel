<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\ImageManipulation;

use MyCLabs\Enum\Enum;

/**
 * @extends Enum<string>
 */
class OutputType extends Enum
{
    private const JPG = 'jpg';

    public static function JPG(): self
    {
        return new self(self::JPG);
    }

    private const WEBP = 'webp';

    public static function WEBP(): self
    {
        return new self(self::WEBP);
    }

    private const PNG = 'png';

    public static function PNG(): self
    {
        return new self(self::PNG);
    }

    private const GIF = 'gif';

    public static function GIF(): self
    {
        return new self(self::GIF);
    }
}
