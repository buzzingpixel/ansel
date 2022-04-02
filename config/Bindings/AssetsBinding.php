<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use Assets_lib;
use Craft;
use craft\services\Assets;

class AssetsBinding
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            Assets::class => static function (): Assets {
                /** @phpstan-ignore-next-line */
                return Craft::$app->getAssets();
            },
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
