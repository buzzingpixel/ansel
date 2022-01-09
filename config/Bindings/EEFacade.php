<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use ExpressionEngine\Service\Model\Facade;

class EEFacade
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            Facade::class => static function (): Facade {
                return ee('Model');
            },
        ];
    }
}
