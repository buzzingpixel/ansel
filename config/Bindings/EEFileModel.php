<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use File_model;

class EEFileModel
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            File_model::class => static function (): File_model {
                ee()->load->model('file_model');

                return ee()->file_model;
            },
        ];
    }
}
