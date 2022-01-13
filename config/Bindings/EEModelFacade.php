<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use ExpressionEngine\Service\Model\Facade as ModelFacade;

class EEModelFacade
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            ModelFacade::class => static function (): ModelFacade {
                return ee('Model');
            },
        ];
    }
}
