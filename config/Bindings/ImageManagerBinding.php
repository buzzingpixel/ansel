<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Shared\TruthyValue;
use EE_Config;
use Intervention\Image\ImageManager;
use Psr\Container\ContainerInterface;

use function assert;
use function extension_loaded;

class ImageManagerBinding
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            ImageManager::class => static function (
                ContainerInterface $di
            ): ImageManager {
                $config = $di->get(EE_Config::class);
                assert($config instanceof EE_Config);

                $forceGD = new TruthyValue(
                    $config->item('forceGD', 'ansel'),
                );

                $driver = 'gd';

                if (
                    $forceGD->isNotTruthy() &&
                    extension_loaded('imagick')
                ) {
                    $driver = 'imagick';
                }

                return new ImageManager(['driver' => $driver]);
            },
        ];
    }
}
