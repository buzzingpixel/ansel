<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use EE_Session;

class EESession
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            EE_Session::class => static function (): EE_Session {
                 return ee()->session;
            },
        ];
    }
}
