<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use ExpressionEngine\Service\URL\URLFactory as CPURLFactory;

class EECPURLFactory
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            CPURLFactory::class => static function (): CPURLFactory {
                return ee('CP/URL');
            },
        ];
    }
}
