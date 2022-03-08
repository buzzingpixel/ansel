<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Treasury\API\Locations;

class TreasuryBindings
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            Locations::class => static function (): Locations {
                return ee('treasury:LocationsAPI');
            },
        ];
    }
}
