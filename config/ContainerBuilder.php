<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig;

use BuzzingPixel\Ansel\Migrate\MigrationsTableContract;
use BuzzingPixel\Ansel\Migrate\MigrationsTableExpressionEngine;
use BuzzingPixel\Ansel\Shared\Meta;
use BuzzingPixel\Container\ConstructorParamConfig;
use BuzzingPixel\Container\Container;
use CI_DB_forge;
use ExpressionEngine\Service\Model\Facade;
use Psr\Container\ContainerInterface;
use RuntimeException;

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
            [
                CI_DB_forge::class => static function (): CI_DB_forge {
                    /**
                     * Make sure the forge class is loaded
                     *
                     * @phpstan-ignore-next-line
                     */
                    ee()->load->dbforge();

                    /** @phpstan-ignore-next-line */
                    return ee()->dbforge;
                },
                MigrationsTableContract::class => static function (
                    ContainerInterface $container
                ): MigrationsTableContract {
                    /** @phpstan-ignore-next-line */
                    if (ANSEL_ENV !== 'ee') {
                        throw new RuntimeException(
                            'Class is not implemented for platform' .
                                ANSEL_ENV,
                        );
                    }

                    /** @phpstan-ignore-next-line */
                    return $container->get(
                        MigrationsTableExpressionEngine::class,
                    );
                },
                Facade::class => static function (): Facade {
                    /** @phpstan-ignore-next-line */
                    return ee('Model');
                },
            ],
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
