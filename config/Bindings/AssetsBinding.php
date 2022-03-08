<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use Assets_lib;

class AssetsBinding
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            /** @phpstan-ignore-next-line */
            Assets_lib::class => static function (): Assets_lib {
                // Add assets libraries and paths
                ee()->load->add_package_path(PATH_THIRD . 'assets/');
                ee()->load->library('assets_lib');

                return ee()->assets_lib;
            },
        ];
    }
}
