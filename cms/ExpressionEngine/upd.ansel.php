<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
// phpcs:disable Squiz.Classes.ClassFileName.NoMatch
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps


declare(strict_types=1);

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Migrate\Migrator;
use BuzzingPixel\AnselConfig\ContainerBuilder;

class Ansel_upd
{
    public function install(): bool
    {
        $container = (new ContainerBuilder())->build();

        $migrator = $container->get(Migrator::class);

        assert($migrator instanceof Migrator);

        $migrator->migrateUp(MigrationContract::EE);

        return true;
    }

    public function uninstall(): bool
    {
        $container = (new ContainerBuilder())->build();

        $migrator = $container->get(Migrator::class);

        assert($migrator instanceof Migrator);

        $migrator->migrateDown(MigrationContract::EE);

        return true;
    }

    /**
     * @param mixed $current
     */
    public function update($current = ''): bool
    {
        $this->install();

        return true;
    }
}
