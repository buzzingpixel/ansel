<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use ExpressionEngine\Addons\FilePicker\Service\FilePicker\Factory;

class FilePickerConfig
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            Factory::class => static function (): Factory {
                return ee('CP/FilePicker');
            },
        ];
    }
}
