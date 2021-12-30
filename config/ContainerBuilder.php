<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig;

use BuzzingPixel\Ansel\Shared\Meta;
use BuzzingPixel\Container\ConstructorParamConfig;
use BuzzingPixel\Container\Container;
use Psr\Container\ContainerInterface;

use function dirname;
use function file_get_contents;
use function json_decode;

class ContainerBuilder
{
    public function build(): ContainerInterface
    {
        $composerJson = json_decode(
            (string) file_get_contents(
                dirname(__DIR__) . '/composer.json',
            )
        );

        return new Container(
            [],
            [
                new ConstructorParamConfig(
                    Meta::class,
                    'version',
                    /** @phpstan-ignore-next-line */
                    $composerJson->version,
                ),
            ],
        );
    }
}
