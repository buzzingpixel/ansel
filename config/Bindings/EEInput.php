<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use EE_Input;

class EEInput
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            EE_Input::class => static function (): EE_Input {
                return ee()->input;
            },
        ];
    }
}
