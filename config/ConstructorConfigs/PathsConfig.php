<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\ConstructorConfigs;

use BuzzingPixel\AnselConfig\Paths;
use BuzzingPixel\Container\ConstructorParamConfig;
use Craft;
use RuntimeException;
use yii\base\Exception;

use function rtrim;

use const DIRECTORY_SEPARATOR;

class PathsConfig
{
    /**
     * @return ConstructorParamConfig[]
     *
     * @throws Exception
     */
    public static function get(): array
    {
        switch (ANSEL_ENV) {
            /** @phpstan-ignore-next-line */
            case 'ee':
                $systemPath = rtrim(
                    SYSPATH,
                    DIRECTORY_SEPARATOR,
                );

                $anselCachePath = $systemPath .
                    DIRECTORY_SEPARATOR .
                    'user' .
                    DIRECTORY_SEPARATOR .
                    'cache' .
                    DIRECTORY_SEPARATOR .
                    'ansel';

                break;

            /** @phpstan-ignore-next-line */
            case 'craft':
                /** @phpstan-ignore-next-line */
                $path = Craft::$app->getPath();

                $storagePath = rtrim(
                    $path->getStoragePath(),
                    DIRECTORY_SEPARATOR,
                );

                $anselCachePath = $storagePath .
                    DIRECTORY_SEPARATOR .
                    'ansel';
                break;

            default:
                $msg = 'Class is not implemented for platform ';

                throw new RuntimeException($msg . ANSEL_ENV);
        }

        return [
            new ConstructorParamConfig(
                Paths::class,
                'anselCachePath',
                $anselCachePath,
            ),
        ];
    }
}
