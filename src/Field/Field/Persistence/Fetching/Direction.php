<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Fetching;

use MyCLabs\Enum\Enum;

/**
 * @extends Enum<string>
 */
class Direction extends Enum
{
    private const ASC = 'asc';

    public static function ASC(): self
    {
        return new self(self::ASC);
    }

    private const DESC = 'desc';

    public static function DESC(): self
    {
        return new self(self::DESC);
    }
}
