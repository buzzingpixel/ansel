<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig;

use BuzzingPixel\Ansel\Shared\Meta;
use BuzzingPixel\AnselConfig\Bindings\CIDBForge;
use BuzzingPixel\AnselConfig\Bindings\CraftDbConnection;
use BuzzingPixel\AnselConfig\Bindings\EEFacade;
use BuzzingPixel\AnselConfig\Bindings\Migrations;
use BuzzingPixel\AnselConfig\Bindings\Twig;
use BuzzingPixel\Container\ConstructorParamConfig;
use BuzzingPixel\Container\Container;
use Psr\Container\ContainerInterface;

use function array_merge;
use function dirname;
use function file_get_contents;
use function json_decode;

class ContainerManager
{
    private static ?ContainerInterface $builtContainer = null;

    public function container(): ContainerInterface
    {
        if (self::$builtContainer !== null) {
            return self::$builtContainer;
        }

        $composerJson = json_decode(
            (string) file_get_contents(
                dirname(__DIR__) . '/composer.json',
            )
        );

        $container = new Container(
            array_merge(
                CIDBForge::get(),
                CraftDbConnection::get(),
                EEFacade::get(),
                Migrations::get(),
                Twig::get(),
            ),
            [
                new ConstructorParamConfig(
                    Meta::class,
                    'version',
                    /** @phpstan-ignore-next-line */
                    $composerJson->version,
                ),
            ],
        );

        self::$builtContainer = $container;

        return $container;
    }
}
