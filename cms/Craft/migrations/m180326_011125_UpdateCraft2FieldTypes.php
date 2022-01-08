<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft\migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Migrate\Migrations\C0009UpdateAnselFieldTypeNameFromCraft2;
use BuzzingPixel\AnselCms\Craft\AnselMigration;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

/**
 * @codeCoverageIgnore
 */
class m180326_011125_UpdateCraft2FieldTypes extends AnselMigration
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function setMigration(
        ContainerInterface $container
    ): MigrationContract {
        /** @phpstan-ignore-next-line */
        return $container->get(C0009UpdateAnselFieldTypeNameFromCraft2::class);
    }
}
