<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\ConstructorConfigs;

use BuzzingPixel\Ansel\License\LicensePing;
use BuzzingPixel\Container\ConstructorParamConfig;
use RuntimeException;

class LicensePingConfig
{
    /**
     * @return ConstructorParamConfig[]
     */
    public static function get(): array
    {
        switch (ANSEL_ENV) {
            /** @phpstan-ignore-next-line */
            case 'ee':
                return [
                    new ConstructorParamConfig(
                        LicensePing::class,
                        'appKey',
                        'ansel-ee',
                    ),
                ];

            /** @phpstan-ignore-next-line */
            case 'craft':
                return [
                    new ConstructorParamConfig(
                        LicensePing::class,
                        'appKey',
                        'ansel-craft',
                    ),
                ];

            default:
                $msg = 'Class is not implemented for platform ';

                throw new RuntimeException($msg . ANSEL_ENV);
        }
    }
}
