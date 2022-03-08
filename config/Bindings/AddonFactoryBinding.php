<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use ExpressionEngine\Service\Addon\Factory;

class AddonFactoryBinding
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            Factory::class => static function (): Factory {
                return ee('Addon');
            },
        ];
    }
}
