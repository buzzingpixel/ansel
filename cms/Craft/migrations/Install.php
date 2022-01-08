<?php

declare(strict_types=1);

namespace BuzzingPixel\AnselCms\Craft\migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Migrate\Migrator;
use BuzzingPixel\AnselConfig\ContainerManager;
use craft\db\Migration;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use function assert;

/**
 * @codeCoverageIgnore
 */
class Install extends Migration
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function safeUp(): bool
    {
        $container = (new ContainerManager())->container();

        $migrator = $container->get(Migrator::class);

        assert($migrator instanceof Migrator);

        $migrator->migrateUp(MigrationContract::CRAFT);

        return true;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function safeDown(): bool
    {
        $container = (new ContainerManager())->container();

        $migrator = $container->get(Migrator::class);

        assert($migrator instanceof Migrator);

        $migrator->migrateDown(MigrationContract::CRAFT);

        return true;
    }
}
