<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
// phpcs:disable Squiz.Classes.ClassFileName.NoMatch
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps


declare(strict_types=1);

use BuzzingPixel\Ansel\Migrate\EeFieldVersionUpdater;
use BuzzingPixel\Ansel\Migrate\EeModuleVersionUpdater;
use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Migrate\Migrator;
use BuzzingPixel\AnselCms\ExpressionEngine\legacy\Updates\V130\Legacy130FieldSettingsUpdater;
use BuzzingPixel\AnselCms\ExpressionEngine\legacy\Updates\V130\Legacy130ImagesUpdater;
use BuzzingPixel\AnselCms\ExpressionEngine\legacy\Updates\V130\Legacy130SettingsUpdater;
use BuzzingPixel\AnselCms\ExpressionEngine\legacy\Updates\V140\Legacy140ImagesUpdater;
use BuzzingPixel\AnselCms\ExpressionEngine\legacy\Updates\V200\Legacy200ImagesUpdater;
use BuzzingPixel\AnselConfig\ContainerManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Ansel_upd
{
    private ContainerInterface $container;

    public function __construct()
    {
        $this->container = (new ContainerManager())->container();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function install(): bool
    {
        $migrator = $this->container->get(Migrator::class);

        assert($migrator instanceof Migrator);

        $migrator->migrateUp(MigrationContract::EE);

        return true;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function uninstall(): bool
    {
        $migrator = $this->container->get(Migrator::class);

        assert($migrator instanceof Migrator);

        $migrator->migrateDown(MigrationContract::EE);

        return true;
    }

    /**
     * @param string|int|float $current
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function update($current = ''): bool
    {
        $this->install();

        /**
         * LEGACY UPDATES
         */

        // Less than 1.3.0
        $compare130 = version_compare(
            (string) $current,
            '1.3.0',
            '<',
        );

        if ($compare130) {
            // Run field settings updater
            $legacy130FieldSettingsUpdater = $this->container->get(
                Legacy130FieldSettingsUpdater::class,
            );
            assert($legacy130FieldSettingsUpdater instanceof Legacy130FieldSettingsUpdater);
            $legacy130FieldSettingsUpdater->process();

            // Run images table updater
            $legacy130ImagesUpdater = $this->container->get(
                Legacy130ImagesUpdater::class,
            );
            assert($legacy130ImagesUpdater instanceof Legacy130ImagesUpdater);
            $legacy130ImagesUpdater->process();

            // Run settings updater
            $legacy130SettingsUpdater = $this->container->get(
                Legacy130SettingsUpdater::class,
            );
            assert($legacy130SettingsUpdater instanceof Legacy130SettingsUpdater);
            $legacy130SettingsUpdater->process();
        }

        // Less than 1.4.0
        $compare140 = version_compare(
            (string) $current,
            '1.4.0',
            '<',
        );

        if ($compare140) {
            // Run images table updater
            $legacy140ImagesUpdater = $this->container->get(
                Legacy140ImagesUpdater::class,
            );
            assert($legacy140ImagesUpdater instanceof Legacy140ImagesUpdater);
            $legacy140ImagesUpdater->process();
        }

        /**
         * Version updates
         */

        // Less than 2.0.0 (or 2.0.0-b.1)
        $compare200 = version_compare(
            (string) $current,
            '2.0.0',
            '<',
        );

        if ($compare200 || $current === '2.0.0-b.1') {
            $legacy200ImagesUpdater = $this->container->get(
                Legacy200ImagesUpdater::class,
            );
            assert($legacy200ImagesUpdater instanceof Legacy200ImagesUpdater);
            $legacy200ImagesUpdater->process();
        }

        /**
         * Ensure module and field records version numbers are up to date
         */

        $fieldVerUpdater = $this->container->get(
            EeFieldVersionUpdater::class,
        );
        assert($fieldVerUpdater instanceof EeFieldVersionUpdater);
        $fieldVerUpdater->update();

        $moduleVerUpdater = $this->container->get(
            EeModuleVersionUpdater::class,
        );
        assert($moduleVerUpdater instanceof EeModuleVersionUpdater);
        $moduleVerUpdater->update();

        return true;
    }
}
