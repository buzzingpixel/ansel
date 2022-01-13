<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Settings\SettingsRepositoryContract;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryCraft;
use BuzzingPixel\Ansel\Settings\SettingsRepositoryEe;
use Psr\Container\ContainerInterface;
use RuntimeException;

class SettingsRepository
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            SettingsRepositoryContract::class => static function (
                ContainerInterface $container
            ): SettingsRepositoryContract {
                switch (ANSEL_ENV) {
                    /** @phpstan-ignore-next-line */
                    case 'ee':
                        /** @phpstan-ignore-next-line */
                        return $container->get(SettingsRepositoryEe::class);

                    /** @phpstan-ignore-next-line */
                    case 'craft':
                        /** @phpstan-ignore-next-line */
                        return $container->get(SettingsRepositoryCraft::class);

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
