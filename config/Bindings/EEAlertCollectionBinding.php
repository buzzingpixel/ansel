<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use ExpressionEngine\Service\Alert\AlertCollection;

class EEAlertCollectionBinding
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            AlertCollection::class => static function (): AlertCollection {
                return ee('CP/Alert');
            },
        ];
    }
}
