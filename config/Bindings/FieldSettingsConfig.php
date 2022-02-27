<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselConfig\Bindings;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollectionValidatorContract as ValidatorContract;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollectionValidatorCraft;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollectionValidatorEe;
use Psr\Container\ContainerInterface;
use RuntimeException;

class FieldSettingsConfig
{
    /**
     * @return callable[]
     */
    public static function get(): array
    {
        return [
            ValidatorContract::class => static function (
                ContainerInterface $container
            ): ValidatorContract {
                switch (ANSEL_ENV) {
                    /** @phpstan-ignore-next-line */
                    case 'ee':
                        /** @phpstan-ignore-next-line */
                        return $container->get(
                            FieldSettingsCollectionValidatorEe::class,
                        );

                    /** @phpstan-ignore-next-line */
                    case 'craft':
                        /** @phpstan-ignore-next-line */
                        return $container->get(
                            FieldSettingsCollectionValidatorCraft::class,
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
