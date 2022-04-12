<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use Craft;
use craft\services\Volumes;

class VolumesBinding
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            Volumes::class => static function (): Volumes {
                /** @phpstan-ignore-next-line */
                return Craft::$app->getVolumes();
            },
        ];
    }
}
