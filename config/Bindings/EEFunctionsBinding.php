<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use EE_Functions;

class EEFunctionsBinding
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            EE_Functions::class => static function (): EE_Functions {
                return ee()->functions;
            },
        ];
    }
}
