<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use Csrf;

class EECsrf
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            Csrf::class => static function (): Csrf {
                return ee()->csrf;
            },
        ];
    }
}
