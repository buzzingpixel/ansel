<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use EE_Lang;

use function assert;

class EELang
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            EE_Lang::class => static function (): EE_Lang {
                $lang = ee()->lang;

                assert($lang instanceof EE_Lang);

                $lang->loadfile('ansel');

                return $lang;
            },
        ];
    }
}
