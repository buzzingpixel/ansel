<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Migrate\MigrationsTableContract;
use BuzzingPixel\Ansel\Migrate\MigrationsTableCraft;
use BuzzingPixel\Ansel\Migrate\MigrationsTableExpressionEngine;
use Psr\Container\ContainerInterface;
use RuntimeException;

class Migrations
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
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
        ];
    }
}
