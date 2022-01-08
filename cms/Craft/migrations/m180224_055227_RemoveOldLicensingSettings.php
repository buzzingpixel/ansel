<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft\migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Migrate\Migrations\C0004RemoveOldLicensingSettings;
use BuzzingPixel\AnselCms\Craft\AnselMigration;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

/**
 * @codeCoverageIgnore
 */
class m180224_055227_RemoveOldLicensingSettings extends AnselMigration
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function setMigration(
        ContainerInterface $container
    ): MigrationContract {
        /** @phpstan-ignore-next-line */
        return $container->get(C0004RemoveOldLicensingSettings::class);
    }
}
