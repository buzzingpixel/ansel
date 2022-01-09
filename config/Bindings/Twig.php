<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\AnselSrc;
use BuzzingPixel\AnselConfig\AnselConfig;
use Psr\Container\ContainerInterface;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader;

use function assert;

class Twig
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            TwigEnvironment::class => static function (
                ContainerInterface $container
            ): TwigEnvironment {
                $loader = $container->get(FilesystemLoader::class);

                assert($loader instanceof FilesystemLoader);

                $loader->addPath(
                    AnselConfig::getPath(),
                    'AnselConfig',
                );

                $loader->addPath(
                    AnselSrc::getPath(),
                    'AnselSrc',
                );

                return new TwigEnvironment(
                    $loader,
                    [],
                );
            },
        ];
    }
}
