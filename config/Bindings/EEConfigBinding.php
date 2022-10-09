<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use EE_Config;

class EEConfigBinding
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            EE_Config::class => static function (): EE_Config {
                return ee()->config;
            },
        ];
    }
}
