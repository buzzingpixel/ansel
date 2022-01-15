<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use Cp;

class EECP
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            Cp::class => static function (): Cp {
                return ee()->cp;
            },
        ];
    }
}
