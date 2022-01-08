<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig;

use BuzzingPixel\Ansel\Migrate\MigrationsTableContract;
use BuzzingPixel\Ansel\Migrate\MigrationsTableCraft;
use BuzzingPixel\Ansel\Migrate\MigrationsTableExpressionEngine;
use BuzzingPixel\Ansel\Shared\Meta;
use BuzzingPixel\Container\ConstructorParamConfig;
use BuzzingPixel\Container\Container;
use CI_DB_forge;
use Craft;
use craft\db\Connection as DbConnection;
use ExpressionEngine\Service\Model\Facade;
use Psr\Container\ContainerInterface;
use RuntimeException;

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
            [
                CI_DB_forge::class => static function (): CI_DB_forge {
                    /**
                     * Make sure the forge class is loaded
                     */
                    ee()->load->dbforge();

                    return ee()->dbforge;
                },
                MigrationsTableContract::class => static function (
                    ContainerInterface $container
                ): MigrationsTableContract {
                    switch (ANSEL_ENV) {
                        /** @phpstan-ignore-next-line */
                        case 'ee':
                            /** @phpstan-ignore-next-line */
                            return $container->get(
                                MigrationsTableExpressionEngine::class,
                            );

                        /** @phpstan-ignore-next-line */
                        case 'craft':
                            /** @phpstan-ignore-next-line */
                            return $container->get(
                                MigrationsTableCraft::class,
                            );

                        default:
                            $msg = 'Class is not implemented for platform ';

                            throw new RuntimeException(
                                $msg . ANSEL_ENV,
                            );
                    }
                },
                Facade::class => static function (): Facade {
                    return ee('Model');
                },
                DbConnection::class => static function (): DbConnection {
                    /** @phpstan-ignore-next-line */
                    return Craft::$app->getDb();
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

        self::$builtContainer = $container;

        return $container;
    }
}
