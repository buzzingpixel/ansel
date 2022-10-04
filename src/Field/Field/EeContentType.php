<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field;

use MyCLabs\Enum\Enum;

/**
 * @extends Enum<string>
 */
class EeContentType extends Enum
{
    private const CHANNEL = 'channel';

    public static function CHANNEL(): self
    {
        return new self(self::CHANNEL);
    }

    private const GRID = 'grid';

    public static function GRID(): self
    {
        return new self(self::GRID);
    }

    private const FLUID = 'fluid_field';

    public static function FLUID(): self
    {
        return new self(self::FLUID);
    }

    private const LOWVARIABLES = 'low_variables';

    public static function LOWVARIABLES(): self
    {
        return new self(self::LOWVARIABLES);
    }

    private const BLOCKS = 'blocks/1';

    public static function BLOCKS(): self
    {
        return new self(self::BLOCKS);
    }
}
