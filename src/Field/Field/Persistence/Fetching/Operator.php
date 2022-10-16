<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Fetching;

use MyCLabs\Enum\Enum;

// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

/**
 * @extends Enum<string>
 */
class Operator extends Enum
{
    public const EQUAL_TO = '=';

    public static function EQUAL_TO(): self
    {
        return new self(self::EQUAL_TO);
    }

    public const NOT_EQUAL_TO = '!=';

    public static function NOT_EQUAL_TO(): self
    {
        return new self(self::NOT_EQUAL_TO);
    }

    public const GREATER_THAN = '>';

    public static function GREATER_THAN(): self
    {
        return new self(self::GREATER_THAN);
    }

    public const GREATER_THAN_OR_EQUAL_TO = '>=';

    public static function GREATER_THAN_OR_EQUAL_TO(): self
    {
        return new self(self::GREATER_THAN_OR_EQUAL_TO);
    }

    public const LESS_THAN = '<';

    public static function LESS_THAN(): self
    {
        return new self(self::LESS_THAN);
    }

    public const LESS_THAN_OR_EQUAL_TO = '<=';

    public static function LESS_THAN_OR_EQUAL_TO(): self
    {
        return new self(self::LESS_THAN_OR_EQUAL_TO);
    }

    public const IN = 'IN';

    public static function IN(): self
    {
        return new self(self::IN);
    }

    public const NOT_IN = 'NOT IN';

    public static function NOT_IN(): self
    {
        return new self(self::NOT_IN);
    }

    public const NONE = '';

    public static function NONE(): self
    {
        return new self(self::NONE);
    }
}
