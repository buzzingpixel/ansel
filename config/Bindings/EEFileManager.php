<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use Filemanager;

class EEFileManager
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            Filemanager::class => static function (): Filemanager {
                ee()->load->library('filemanager');

                return ee()->filemanager;
            },
        ];
    }
}
