<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use GuzzleHttp\Client as GuzzleClient;

class GuzzleConfig
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            GuzzleClient::class => static function (): GuzzleClient {
                return new GuzzleClient(['verify' => false]);
            },
        ];
    }
}
